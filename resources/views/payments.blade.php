@extends('layouts.app')

@section('title', 'Payments')

@section('content')<!DOCTYPE html>


<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Payment</title>

  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: Arial, Helvetica, sans-serif;
    }

    body {
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      background: #ffffff;
    }

    .page {
      width: 950px;
      min-height: 550px;
      background: #d6e6f7; /* light blue */
      padding: 30px 35px;
      margin-top: 20px;
    }

    .title {
      font-size: 48px;
      margin-bottom: 25px;
    }

    /* main horizontal layout: left green box + right blue box */
    .content-row {
      display: flex;
      gap: 25px;
      align-items: stretch;
    }

    .left-panel {
      flex: 1 1 0;
      background: #bfddb1; /* light green */
      border: 1px solid #8aa172;
      padding: 40px 60px;
      display: flex;
      justify-content: center;
      align-items: flex-start;
    }

    .right-panel {
      width: 220px;
      background: #a7bddd; /* darker blue */
      border: 1px solid #6f7fa2;
    }

    /* form layout inside left panel */
    .patient-form {
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 30px;
    }

    .patient-row {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .patient-label {
      background: #7da4e3;
      padding: 8px 18px;
      min-width: 110px;
      border: 1px solid #5c729c;
      text-align: center;
    }

    .patient-input {
      width: 200px;
      height: 30px;
      border: 1px solid #808f63;
      background: #d6e6b7;
    }

    /* ok / cancel buttons row */
    .button-row {
      align-self: center;
      display: flex;
      gap: 35px;
      margin-top: 10px;
    }

    .btn {
      background: #9bbf61;
      border: 1px solid #6e8642;
      padding: 6px 28px;
      font-size: 16px;
      cursor: pointer;
    }

    /* update button centered below */
    .update-row {
      align-self: center;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div class="page">
    <h1 class="title">Payment</h1>

    <div class="content-row">
      <!-- Left green area -->
      <div class="left-panel">
        <form class="patient-form">
          <div class="patient-row">
            <span class="patient-label">Patient ID</span>
            <input class="patient-input" type="text" />
          </div>
          <div class="patient-row">
            <span class="patient-label">Patient ID</span>
            <input class="patient-input" type="text" />
          </div>
          <div class="patient-row">
            <span class="patient-label">Patient ID</span>
            <input class="patient-input" type="text" />
          </div>

          <div class="button-row">
            <button type="button" class="btn">ok</button>
            <button type="button" class="btn">cancel</button>
          </div>

          <div class="update-row">
            <button type="button" class="btn">update</button>
          </div>
        </form>
      </div>

      <!-- Right tall blue area -->
      <div class="right-panel"></div>
    </div>
  </div>
</body>
</html>
