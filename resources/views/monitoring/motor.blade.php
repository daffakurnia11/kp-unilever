@extends('layouts.main')

@section('content')

  <!--Header-->
  <div class="page-breadcrumb d-flex flex-column flex-md-row align-items-center mb-3">
    <div class="breadcrumb-title pe-md-3">Motor1 Monitoring</div>
    <div class="ps-md-3 ms-md-auto mx-auto mx-md-0 mt-3 mt-md-0">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-0">
          <li class="breadcrumb-item">
            <a href="/">
              Dashboard
            </a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">Motor1</li>
        </ol>
      </nav>
    </div>
  </div>
  <!--end of Header--> 

  <h6 class="mb-0 text-uppercase">ADXL Vibration Sensor Monitoring</h6>
  <hr>
  <div class="row justify-content-center">
    <div class="col-xl-6">
      <div class="card">
        <div class="card-body">
          <div id="vibrationXChart"></div>
        </div>
      </div>
    </div>
    <div class="col-xl-6">
      <div class="card">
        <div class="card-body">
          <div id="vibrationYChart"></div>
        </div>
      </div>
    </div>
    <div class="col-xl-6">
      <div class="card">
        <div class="card-body">
          <div id="vibrationZChart"></div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('javascript')
<script>
  const url = 'http://192.168.137.109/kp-unilever/public';

  var chartOption = {
    chart: {
      type: 'line',
      height: 300
    },
    series: [{
      name: 'temperature',
      data: []
    }],
    stroke: {
      show: true,
      curve: 'smooth',
      lineCap: 'butt',
      colors: undefined,
      width: 3,
      dashArray: 0,      
    },
    xaxis: {
      labels: {
        show: false,
      },
      tooltip: {
        enabled: false,
      }
    },
    title: {
      text: undefined,
      align: 'center',
      style: {
        fontSize:  '14px',
        fontWeight:  'bold',
      },
    }
  }

  var vibrationXChart = new ApexCharts(document.getElementById("vibrationXChart"), chartOption);
  vibrationXChart.render();
  var vibrationYChart = new ApexCharts(document.getElementById("vibrationYChart"), chartOption);
  vibrationYChart.render();
  var vibrationZChart = new ApexCharts(document.getElementById("vibrationZChart"), chartOption);
  vibrationZChart.render();
  
  var updateChart = function () {
      var dataVibrationX = [];
      var dataVibrationY = [];
      var dataVibrationZ = [];

      $.ajax({
        type: "GET",
        url: url + '/api/motor_sensor/Motor1/vibration',
        dataType: 'JSON',
        success: function (resp) {
          resp.data[0].vibration.forEach(data => {
            let time = moment(data.created_at).format("MMM, DD YYYY - HH:mm:ss");
            let dataX = {x: time, y: data.x_axis};
            dataVibrationX.push(dataX);

            let dataY = {x: time, y: data.y_axis};
            dataVibrationY.push(dataY);

            let dataZ = {x: time, y: data.z_axis};
            dataVibrationZ.push(dataZ);
          });

          vibrationXChart.updateOptions({
            series: [{
              name: 'X Axis',
              data: dataVibrationX
            }],
            title: {
              text: "X Axis Vibration"
            }
          });

          vibrationYChart.updateOptions({
            series: [{
              name: 'Y Axis',
              data: dataVibrationY
            }],
            title: {
              text: "Y Axis Vibration"
            }
          });

          vibrationZChart.updateOptions({
            series: [{
              name: 'Z Axis',
              data: dataVibrationZ
            }],
            title: {
              text: "Z Axis Vibration"
            }
          });
        }
      });
    }

    updateChart();
    setInterval(() => {
      updateChart();
    }, 5000);
  </script>
  @endsection