<!DOCTYPE html>
<html lang="en">

<html>
<head>
  <meta charset="UTF-8">

  <title>Instructions for using the math testing website</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>

  <link rel="shortcut icon" href="../images/favicon_svf.ico" type="image/x-icon ">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.0-beta3/css/bootstrap.min.css">

  <link rel="stylesheet" href="base.css">

</head>
<style>
  a:hover{
    background-color: #2F4858;
  }
</style>
<body style="margin: 0 5rem 3rem 5rem; display:flex; flex-direction: column">
<div class="d-flex justify-content-between align-items-center">
  <a href="index.php" style="border: none"><i class="bi bi-arrow-left-circle m-lg-1" style="font-size: 2rem;"></i></a>
  <button onclick="goToOtherPage()" id="lan" style="margin-top: 13px;"><i class="bi bi-translate m-lg-1" style="font-size: 2rem;"></i></button>
  <script>
    function goToOtherPage() {window.location.href = "tutorial_sk.php";}
  </script>
</div>
<div id="content">
  <h1 style="font-size: 2.5rem;text-align: center;">Instructions for using the math testing website</h1>

  <h2>Log In</h2>
  <ol>
    <li>On the main page, click the "Log In" button.</li>
    <li>Enter your username and password. For students, it's `student1` or `student2` and for teachers, it's `teacher1` or `teacher2`. The password for all accounts is `00000000`.</li>
    <li>Click the "Log In" button.</li>
  </ol>

  <h2>For Students</h2>
  <ol>
    <li>After logging in, you have the option on the right to select a set of tasks from which you want to generate tasks.</li>
    <li>Click the "Generate" button. Examples will be generated for you.</li>
    <li>You can start solving tasks. Click on the example you want to solve.</li>
    <li>Enter the result using the virtual math keyboard and then click the "Submit" button.</li>
    <li>Submitted examples will have an orange background and you will earn points for them.</li>
  </ol>

  <h2>For Teachers</h2>
  <ol>
    <li>After logging in, you can set which sets of tasks students can solve, how many points they will earn for each set, and when they can be solved (date).</li>
    <li>You can follow the table of all students, where information is provided about how many tasks each student generated, how many they submitted, and how many points they earned.</li>
    <li>The table can be sorted by clicking on the columns.</li>
    <li>You can export this table by clicking the "Export" button. You can download it in PDF, CSV and other formats.</li>
    <li>You also have a table with the names of students and tasks that they generated, submitted, and scored.</li>
  </ol>

  <h2>Changing Language</h2>
  <ul>
    <li>On the page, you can change the language at any time by clicking on the small language selection button located in the upper right corner of the page.</li>
  </ul>

  <p>Note: This is the basic use of the website. There may be other functions that are not listed in this guide. If you have any other questions or need more information, don't hesitate to contact us.</p>
</div>


<button class="big-button" onclick="downloadPDF()" style="font-size: 1rem;width: 150px;height: 50px; align-self: center">Download as PDF</button>

<script>
  function downloadPDF() {

    var element = document.getElementById('content');
    var opt = {
      margin:       0.5,
      filename:     'navod.pdf',
      image:        { type: 'png', quality: 1 },
      html2canvas:  { scale: 2 },
      jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
    };

    html2pdf().set(opt).from(element).save();
  }
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.0-beta3/js/bootstrap.bundle.min.js"></script>

</body>
</html>

