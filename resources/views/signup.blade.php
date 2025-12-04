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
      background-image: url('retirement_home.jpg');
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
      margin: 5px 0;
    }

    #request_btn {
      display: block;
      margin: 40px auto 0 auto;
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

    <form id="signup_form">
      <div id="input_boxes_parent">
        <div id="first_column">
          <input type="email" name="email" id="Email_Input" placeholder="Email" required>
          <input type="password" name="password" id="Password_Input" placeholder="Password" required>
          <input type="text" name="first_name" id="first_input" placeholder="First Name" required>
          <input type="text" name="last_name" id="last_input" placeholder="Last Name" required>
          <input type="date" name="dob" id="dob_input" placeholder="Date of Birth" required>
        </div>

        <div id="second_column">
          <select name="role" id="role_select" required>
            <option value="0">Select Your Role</option>
            <option value="1">Patient</option>
            <option value="2">Doctor</option>
            <option value="3">Supervisor</option>
            <option value="4">Admin</option>
          </select>

          <input type="text" name="emergency_contact" class="patient_inputs" id="emergency_contact_input" placeholder="Emergency Contact Full Name">
          <input type="text" name="relation_emergency" class="patient_inputs" id="relation_emergency_input" placeholder="Relation to Emergency Contact">
        </div>
      </div>

      <button type="submit" id="request_btn">Request</button>
    </form>
  </div>

  <script>
    const select_tag = document.getElementById("role_select");
    const emergency_contact_input = document.getElementById("emergency_contact_input");
    const relation_emergency_contact = document.getElementById("relation_emergency_input");

    function togglePatientInputs() {
      if (select_tag.value === "1") {
        emergency_contact_input.style.display = "block";
        relation_emergency_contact.style.display = "block";
        emergency_contact_input.required = true;
        relation_emergency_contact.required = true;
      } else {
        emergency_contact_input.style.display = "none";
        relation_emergency_contact.style.display = "none";
        emergency_contact_input.required = false;
        relation_emergency_contact.required = false;
      }
    }

    togglePatientInputs();
    select_tag.addEventListener("change", togglePatientInputs);

    // Handle form submission
    const form = document.getElementById("signup_form");
    form.addEventListener("submit", function(e) {
      e.preventDefault();

      const data = {
        email: document.getElementById("Email_Input").value,
        password: document.getElementById("Password_Input").value,
        first_name: document.getElementById("first_input").value,
        last_name: document.getElementById("last_input").value,
        dob: document.getElementById("dob_input").value,
        role: document.getElementById("role_select").value,
        emergency_contact: document.getElementById("emergency_contact_input").value,
        relation_emergency: document.getElementById("relation_emergency_input").value
      };

      fetch("/signup_endpoint.php", { // replace with your backend URL
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
      })
      .then(res => res.json())
      .then(response => {
        if(response.status === "success"){
          alert("Request sent successfully!");
          form.reset();
          togglePatientInputs();
        } else {
          alert("Error: " + response.message);
        }
      })
      .catch(err => {
        console.error(err);
        alert("Error sending request");
      });
    });
  </script>
</body>
</html>
