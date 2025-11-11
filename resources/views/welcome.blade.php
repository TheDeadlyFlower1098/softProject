<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, Helvetica, sans-serif;
      color: rgb(60, 120, 216);
      background-image: url('{{ asset('images/retirement_home.jpg') }}');
      background-size: cover;
      background-position: center;
      height: 100vh;
      display: flex;
      justify-content: flex-end;  
      align-items: center;        
      padding-right: 150px;
    }

    #parent-div {
      background-color: rgb(182, 215, 168);
      width: 500px;
      height: 600px;
      padding: 40px;
      border-radius: 5px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    }

    h1 {
      font-weight: 400;
      font-size: 48px;
      margin-top: 0;
      margin-bottom: 30px;
    }

    #input_boxes_parent {
      display: flex;
      justify-content: space-between;
      gap: 25px;
    }

    .patient_inputs {
      display: none;
    }

    #first_column, #second_column {
      display: flex;
      flex-direction: column;
      gap: 15px;
      width: 48%;
    }

    input,
    select {
      font-size: 18px;
      background-color: rgb(201, 218, 248);
      border: 1px solid black;
      border-radius: 3px;
      padding: 10px;
      margin: 5px;
    }

    #request_btn {
      display: block;
      margin: 75px auto 0 auto;
      width: 200px;
      padding: 10px;
      background-color: rgb(111, 168, 220);
      border: none;
      color: black;
      font-weight: bold;
      font-size: 22px;
      border-radius: 3px;
      cursor: pointer;
    }

    #request_btn:hover {
      background-color: rgb(90, 150, 200);
      color: white;
    }
  </style>
</head>
<body>
  <div id="parent-div">
    <h1>Sign Up</h1>

    <div id="input_boxes_parent">
      <div id="first_column">
        <input type="text" name="Email_Input" id="Email_Input" placeholder="Email">
        <input type="text" name="Password_Input" id="Password_Input" placeholder="Password">
        <input type="text" name="first_input" id="first_input" placeholder="First Name">
        <input type="text" name="last_input" id="last_input" placeholder="Last Name">
        <input type="text" name="dob_input" id="dob_input" placeholder="Date of Birth">
      </div>

      <div id="second_column">
        <select name="role_select" id="role_select">
          <option value="0">Select Your Role</option>
          <option value="1">Patient</option>
          <option value="2">Doctor</option>
          <option value="3">Supervisor</option>
          <option value="4">Admin</option>
        </select>

        <input type="text" name="emergency_contact" class="patient_inputs" id="emergency_contact_input" placeholder="Emergency Contact Full Name">
        <input type="text" name="relation_emergency_contact" class="patient_inputs" id="relation_emergency_input" placeholder="Relation to Emergency Contact (i.e Son, Daughter, etc.)">
      </div>
    </div>

    <button id="request_btn">Request</button>
  </div>
</body>
<script>
  const select_tag = document.getElementById("role_select")
  const emergency_contact_input = document.getElementById("emergency_contact_input")
  const relation_emergency_contact = document.getElementById("relation_emergency_input")
  function togglePatientInputs() {
    if (select_tag.value === "1") {
      emergency_contact_input.style.display = "block";
      relation_emergency_contact.style.display = "block";
    } else {
      emergency_contact_input.style.display = "none";
      relation_emergency_contact.style.display = "none";
    }
  }

  // Run once when page loads
  togglePatientInputs();

  select_tag.addEventListener("change", togglePatientInputs);
</script>
</html>
