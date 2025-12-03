<!-- index.html -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Family member</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<style>
* {
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

/* Outer container */
.app {
  width: 900px;
  min-height: 550px;
  background: #d6e6f7;           /* light blue */
  padding: 30px 40px;
  margin-top: 20px;
}

/* Title */
.title {
  font-size: 48px;
  margin-bottom: 40px;
}

/* Top section layout */
.top-row {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 30px;
}

/* Left column with the two rows of inputs */
.fields {
  display: flex;
  flex-direction: column;
  gap: 25px;
}

/* One label + input row */
.field-row {
  display: flex;
  align-items: center;
  gap: 10px;
}

/* Green label boxes */
.field-label {
  background: #9bbf61;           
  padding: 8px 14px;
  min-width: 120px;
  text-align: center;
  border: 1px solid #6e8642;
}

/* Blue inputs */
.field-input {
  width: 180px;
  height: 30px;
  border: 1px solid #6f7fa2;
  background: #b7c9ea;
}

/* Buttons on the right */
.buttons {
  display: flex;
  flex-direction: row;
  gap: 20px;
  margin-top: 10px;
}

.btn {
  background: #9bbf61;
  border: 1px solid #6e8642;
  padding: 8px 35px;
  font-size: 16px;
  cursor: pointer;
}

/* Big patient-details box */
.patient-box {
  margin-top: 10px;
  background: #bfddb1;           /* light green */
  border: 1px solid #8aa172;
  height: 320px;
  padding: 30px;
  display: flex;
  align-items: flex-start;
}

.patient-label {
  font-size: 20px;
}

</style>
<body>
  <div class="app">
    <h1 class="title">Family member</h1>

    <!-- Top area: inputs + buttons -->
    <div class="top-row">
      <!-- Left: input fields -->
      <div class="fields">
        <div class="field-row">
          <label class="field-label" for="familyCode">Family code</label>
          <input class="field-input" id="familyCode" type="text" />
        </div>

        <div class="field-row">
          <label class="field-label" for="patientId">Patient ID</label>
          <input class="field-input" id="patientId" type="text" />
        </div>
      </div>

      <!-- Right: buttons -->
      <div class="buttons">
        <button class="btn">ok</button>
        <button class="btn">cancel</button>
      </div>
    </div>

    <!-- Bottom big box -->
    <div class="patient-box">
      <span class="patient-label">Patient details</span>
    </div>
  </div>
</body>
</html>
