<?php
$burn_times   = [1, 2, 5, 10, 20];
$interval_times = [0.5, 1, 2, 3, 5, 10];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>reaction</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #0a0a0a;
      color: #ddd;
      text-align: center;
      padding: 20px;
      margin: 0;
    }
    h1 { margin: 30px 0 50px; font-size: 2.2rem; }
    .container {
      display: flex;
      justify-content: center;
      gap: 100px;
      margin: 40px 0 60px;
    }
    .light {
      width: 200px;
      height: 200px;
      border-radius: 50%;
      background: #111;
      box-shadow: 0 0 25px #000 inset;
      transition: all 0.2s ease;
    }
    .light.on {
      box-shadow: 0 0 100px 50px currentColor;
      background: radial-gradient(circle at 40% 40%, white 5%, currentColor 40%, #000 90%);
    }

    .controls {
      margin: 40px 0;
    }
    label { font-size: 1.2rem; margin-right: 12px; }
    select, button {
      font-size: 1.3rem;
      padding: 12px 24px;
      margin: 10px;
      cursor: pointer;
      border: none;
      border-radius: 6px;
      min-width: 140px;
    }
    button {
      background: #444;
      color: white;
    }
    button:hover { background: #666; }
    #startBtn { background: #2a8c4a; }
    #startBtn:hover { background: #3aa860; }
    #stopBtn  { background: #a83232; }
    #stopBtn:hover  { background: #c13e3e; }

    #status {
      font-size: 1.5rem;
      min-height: 2em;
      margin: 30px 0;
      color: #aaa;
    }
  </style>
</head>
<body>

  <h1>Тренировка реакции — два фонарика</h1>

  <div class="container">
    <div id="left"  class="light"></div>
    <div id="right" class="light"></div>
  </div>

  <div id="status">Выбери настройки и нажми Старт</div>

  <div class="controls">
    <div>
      <label for="burn">Время горения:</label>
      <select id="burn">
        <?php foreach ($burn_times as $sec): ?>
          <option value="<?= $sec ?>"><?= $sec ?> сек</option>
        <?php endforeach; ?>
      </select>
    </div>

    <div style="margin-top: 20px;">
      <label for="interval">Пауза между:</label>
      <select id="interval">
        <?php foreach ($interval_times as $sec): ?>
          <option value="<?= $sec ?>" <?= $sec == 2 ? 'selected' : '' ?>><?= $sec ?> сек</option>
        <?php endforeach; ?>
      </select>
    </div>

    <br><br>

    <button id="startBtn" onclick="startTraining()">Старт</button>
    <button id="stopBtn"  onclick="stopTraining()">Стоп</button>
  </div>

  <script>
    let isRunning = false;
    let nextTimeout = null;
    let offTimeout = null;

    const colors = [
      {name: 'Красный',  hex: '#ff2d55'},
      {name: 'Зелёный', hex: '#00ff85'},
      {name: 'Синий',   hex: '#3b82f6'},
      {name: 'Жёлтый',  hex: '#ffcc00'}
    ];

    function getRandomColor() {
      return colors[Math.floor(Math.random() * colors.length)];
    }

    function getRandomSide() {
      return Math.random() < 0.5 ? 'left' : 'right';
    }

    function lightOn() {
      if (!isRunning) return;

      const side = getRandomSide();
      const colorObj = getRandomColor();

      const el = document.getElementById(side);
      el.classList.add('on');
      el.style.color = colorObj.hex;

      document.getElementById('status').textContent = 
        `Горит ${side === 'left' ? 'ЛЕВЫЙ' : 'ПРАВЫЙ'} — ${colorObj.name}`;

      const burnSec = parseFloat(document.getElementById('burn').value);
      offTimeout = setTimeout(() => {
        lightOff();
        if (isRunning) {
          const intervalSec = parseFloat(document.getElementById('interval').value);
          nextTimeout = setTimeout(lightOn, intervalSec * 1000);
        }
      }, burnSec * 1000);
    }

    function lightOff() {
      document.querySelectorAll('.light').forEach(el => {
        el.classList.remove('on');
        el.style.color = '';
      });
      document.getElementById('status').textContent = 'Пауза...';
    }

    function startTraining() {
      if (isRunning) return;
      isRunning = true;
      document.getElementById('status').textContent = 'Тренировка началась...';
      lightOn(); 
    }

    function stopTraining() {
      isRunning = false;
      if (nextTimeout) clearTimeout(nextTimeout);
      if (offTimeout)  clearTimeout(offTimeout);
      lightOff();
      document.getElementById('status').textContent = 'Остановлено';
    }
  </script>

</body>
</html>
