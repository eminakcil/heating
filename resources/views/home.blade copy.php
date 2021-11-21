<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport"
    content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="X-UA-Compatible"
    content="ie=edge">
  <meta name="apple-mobile-web-app-title"
    content="Heating System">
  <title>heating-system</title>
  <link rel="stylesheet"
    href="{{ mix('css/app.css') }}">
</head>

<body class="bg-dark">

  <div class="container py-3">
    <div class="row d-flex">
      <div class="col-9 mb-4">
        <div class="card h-100 bg-dark text-light border-secondary">
          <div class="card-body d-flex justify-content-around">
            <button class="btn btn-outline-danger touch-action-manipulation"
              onclick="decreaseTarget()">
              -
            </button>
            <span class="h3"><span id="targetTemperature"></span> °C</span>
            <button class="btn btn-outline-success touch-action-manipulation"
              onclick="increaseTarget()">
              +
            </button>
          </div>
        </div>
      </div>

      <div class="col-3 mb-4">
        <div class="card h-100 bg-dark text-light border-secondary">
          <div class="card-body d-flex justify-content-around">
            <button class="btn btn-outline-primary touch-action-manipulation"
              onclick="setTarget()">
              Ok
            </button>
          </div>
        </div>
      </div>

      <div class="col-12 mb-4 col-md-6">
        <div class="card h-100 bg-dark text-light border-secondary">
          <div class="card-body d-flex justify-content-around">
            <span class="h3"><span id="currentTemperature"></span> °C</span>
          </div>
        </div>
      </div>

      <div class="col-12 mb-4 col-md-6">
        <div class="card bg-dark text-light border-secondary">
          <div class="card-body d-flex justify-content-around">
            <button id="statusOn"
              onclick="toggleStatus()"
              class="btn btn-outline-success touch-action-manipulation">
              Aç
            </button>
            <button id="statusOff"
              onclick="toggleStatus()"
              class="btn btn-outline-danger touch-action-manipulation">
              Kapat
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ mix('js/app.js') }}"></script>
  <script>
    let currentTemperature = 0;
    let targetTemperature = 0;
    let status = false;

    const init = async () => {
      await axios.get('/temperature').then(({
        data
      }) => {
        currentTemperature = parseFloat(data).toFixed(2);
      });

      await axios.get('/target').then(({
        data
      }) => {
        targetTemperature = parseFloat(data).toFixed(2);
      });

      await axios.get('/status').then(({data}) => {
        status = data == '1';
      });
      render();
    };

    const render = () => {
      $('#currentTemperature').text(currentTemperature);
      $('#targetTemperature').text(targetTemperature);
      if (status) {
        $('#statusOn').hide();
        $('#statusOff').show();
      } else {
        $('#statusOn').show();
        $('#statusOff').hide();
      }
    };

    const toggleStatus = () => {
      status = !status;
      if (status) {
        axios.post('/status', {
            value: 'on'
          })
          .then(response => {
            if (response.data == 'ok') {
              $('#statusOn').hide();
              $('#statusOff').show();
            }
          });
      } else {
        axios.post('/status', {
            value: 'off'
          })
          .then(response => {
            if (response.data == 'ok') {
              $('#statusOn').show();
              $('#statusOff').hide();
            }
          });
      }
    };

    const increaseTarget = () => {
      targetTemperature = parseFloat(targetTemperature) + 0.5;
      render();
    };

    const decreaseTarget = () => {
      targetTemperature = parseFloat(targetTemperature) - 0.5;
      render();
    };

    const setTarget = () => {
      axios.post('/target', {
          value: targetTemperature
        })
        .then(({
          data
        }) => {
          if (data == 'ok') {
            axios.get('/target').then(({
              data
            }) => {
              targetTemperature = parseFloat(data).toFixed(2);
              render();
            });
          }
        });
    };

    init();
  </script>
</body>

</html>
