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

      <div class="col-12 mb-4">
        <div style="height: 2px; width: 100%; position: relative; background-color: orange;">
          <div id="timer" style="height: 100%; position: absolute; top: 0; left: 0; background-color: red;"></div>
        </div>
      </div>

      <div class="col-12 mb-4">
        <div class="card bg-dark text-light border-secondary">
          <div class="card-body d-flex justify-content-around">
            <span id="relayOn">Yanıyor</span>
            <span id="relayOff">Yanmıyor</span>
          </div>
        </div>
      </div>
      
      <div class="col-12 mb-4">
        <div class="card bg-dark text-light border-secondary">
          <div class="card-body d-flex justify-content-around">
            <canvas id="temperatureChart"></canvas>
          </div>
        </div>
      </div>
      
      <div class="col-12 mb-4">
        <div class="card bg-dark text-light border-secondary">
          <div class="card-body d-flex justify-content-around">
            <canvas id="relayChart"></canvas>
          </div>
        </div>
      </div>

    </div>
  </div>

  <style>
    @keyframes timer {
      from {width: 0;}
      to {width: 100%;}
    }
    .timer5 {
      animation-name: timer;
      animation-duration: 5s;
      animation-direction: alternate;
      animation-iteration-count: infinite;
      animation-timing-function: linear;
    }
  </style>

  <script src="{{ mix('js/app.js') }}"></script>
  <script>
    let currentTemperature = 0;
    let targetTemperature = 0;
    let status = false;
    let relay = false;

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

      await axios.get('/relay').then(({data}) => {
        relay = data == '1';
      });
      render();

      let interval = setInterval(() => {
        $('#timer').removeClass("timer5");
        $('#timer').addClass("timer5");
        update();
      }, 5000);
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

      if (relay) {
        $('#relayOn').show();
        $('#relayOff').hide();
      } else {
        $('#relayOn').hide();
        $('#relayOff').show();
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

    const update = () => {
      console.log('update');
      axios.get('/temperature')
        .then(({data}) => {
          currentTemperature = parseFloat(data).toFixed(2);
          $('#currentTemperature').text(currentTemperature);
        });
      axios.get('/target')
        .then(({data}) => {
          targetTemperature = parseFloat(data).toFixed(2);
          $('#targetTemperature').text(targetTemperature);
        });

      axios.get('/status')
        .then(({data}) => {
          status = data == '1';
          if (status) {
            $('#statusOn').hide();
            $('#statusOff').show();
          } else {
            $('#statusOn').show();
            $('#statusOff').hide();
          }
        });

      axios.get('/relay')
        .then(({data}) => {
          relay = data == '1';
          if (relay) {
            $('#relayOn').show();
            $('#relayOff').hide();
          } else {
            $('#relayOn').hide();
            $('#relayOff').show();
          }
        });
    };

    init();

    const temperatureChart = new window.Chart(
      document.getElementById('temperatureChart'),{
      type: 'line',
      data: {
        datasets: [{
          label: 'Sıcaklık',
          backgroundColor: 'rgb(255, 99, 132)',
          borderColor: 'rgb(255, 99, 132)',
          data: [],
        }],
      },
      options: {
        scales: {
          x: {
            adapters:{
              date: {
                locale: tr
              }
            },
            type: 'time',
            time: {
              unit: 'minute'
            }
          }
        }
      },
    });
    
    const relayChart = new window.Chart(
      document.getElementById('relayChart'),{
        type: 'line',
        data: {
          datasets: [{
            label: 'Yanım',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: [],
            stepped: true,
          }]
        },
        options: {
          scales: {
            x: {
              adapters:{
                date: {
                  locale: tr
                }
              },
              type: 'time',
              time: {
                unit: 'minute'
              }
            }
          }
        }
      });


    const addData = (chart, label, data) => {
      chart.data.labels.push(label);
      chart.data.datasets.forEach((dataset) => {
        dataset.data.push(data)
      });
      chart.update();
    };

    axios.get('/temperature/all').then(response=>{
      response.data.forEach(data=>{
        addData(temperatureChart, data.created_at, data.value);
      })
    });

    axios.get('/relay/all').then(response=>{
      response.data.forEach(data=>{
        addData(relayChart, data.created_at, data.value);
      })
    });

  </script>
</body>

</html>
