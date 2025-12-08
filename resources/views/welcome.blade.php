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
      transition: 0.2s ease-in;
    }
  </style>
</head>
<body>
  <div id="parent-div">

    @if ($errors->any())
      <div style="background:#f8d7da; color:#721c24; padding:8px 12px; border-radius:4px; margin-bottom:10px; border:1px solid #f5c6cb;">
          <ul style="margin:0; padding-left:18px;">
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif

  @if (session('success'))
      <div style="background:#d4edda; color:#155724; padding:8px 12px; border-radius:4px; margin-bottom:10px; border:1px solid #c3e6cb;">
          {{ session('success') }}
      </div>
  @endif


    <form action="{{ route( 'signup.store') }}" method="POST">
      @csrf
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
            <option value="5">Family</option>
          </select>

          {{-- Patient-only fields --}}
          <input type="text" name="emergency_contact" class="patient_inputs" id="emergency_contact_input" placeholder="Emergency Contact Full Name">
          <input type="text" name="relation_emergency_contact" class="patient_inputs" id="relation_emergency_input" placeholder="Relation to Emergency Contact (i.e Son, Daughter, etc.)">
          
          {{-- Family-only field: patient ID they want to view --}}
          <input
            type="text"
            name="linked_patient_identifier"
            id="linked_patient_identifier_input"
            placeholder="Patient ID Number (for Family Member)"
            style="display:none;"
          >
        </div>
      </div>
      <button id="request_btn">Request</button>
      <p style="text-align: center; padding-top: 5px;">
        Already a User? Sign in <a href="{{ route('login') }}">Here</a>
      </p>
    </form>
  </div>
</body>
<script>
  const select_tag = document.getElementById("role_select");
  const emergency_contact_input = document.getElementById("emergency_contact_input");
  const relation_emergency_contact = document.getElementById("relation_emergency_input");
  const family_patient_input = document.getElementById("linked_patient_identifier_input");

  function toggleRoleInputs() {
    if (select_tag.value === "1") {
      // Patient selected
      emergency_contact_input.style.display = "block";
      relation_emergency_contact.style.display = "block";
      family_patient_input.style.display = "none";
    } else if (select_tag.value === "5") {
      // Family Member selected
      emergency_contact_input.style.display = "none";
      relation_emergency_contact.style.display = "none";
      family_patient_input.style.display = "block";
    } else {
      // Any other role
      emergency_contact_input.style.display = "none";
      relation_emergency_contact.style.display = "none";
      family_patient_input.style.display = "none";
    }
  }

  // Run once on page load (in case of validation errors / old value)
  toggleRoleInputs();

  select_tag.addEventListener("change", toggleRoleInputs);
</script>

</html>
