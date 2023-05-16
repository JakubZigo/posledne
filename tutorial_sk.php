<!DOCTYPE html>
<html lang="en">

<html>
<head>
  <meta charset="UTF-8">

  <title>Návod na použitie webovej stránky pre matematické testovanie</title>
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
  <a href="index.php" style="border: none; "><i class="bi bi-arrow-left-circle m-lg-1" style="font-size: 2rem;"></i></a>
    <button onclick="goToOtherPage()" id="lan" style="margin-top: 13px;"><i class="bi bi-translate m-lg-1" style="font-size: 2rem;"></i></button>
  <script>
    function goToOtherPage() {window.location.href = "tutorial_en.php";}
  </script>
</div>
<div id="content">
  <h1 style="font-size: 2.5rem;text-align: center;">Návod na použitie webovej stránky pre matematické testovanie</h1>

  <h2>Prihlásenie</h2>
  <ol>
    <li>Na hlavnej stránke kliknite na tlačidlo "Prihlásiť sa".</li>
    <li>Vložte svoje užívateľské meno a heslo. Pre študentov sú to `student1` alebo `student2` a pre učiteľov `teacher1` alebo `teacher2`. Heslo pre všetky účty je `00000000`.</li>
    <li>Kliknite na tlačidlo "Prihlásiť sa".</li>
  </ol>

  <h2>Pre študentov</h2>
  <ol>
    <li>Po prihlásení máte na pravo možnosť vybrať si sadu úloh, z ktorých chcete mať vygenerované úlohy.</li>
    <li>Kliknite na tlačidlo "Generovať". Vygenerujú sa vám príklady.</li>
    <li>Môžete začať riešiť úlohy. Kliknite na príklad, ktorý chcete riešiť.</li>
    <li>Zadajte výsledok pomocou virtuálnej matematickej klávesnice a potom kliknite na tlačidlo "Odovzdať".</li>
    <li>Odovzdané príklady budú mať oranžové pozadie a získate za ne body.</li>
  </ol>

  <h2>Pre učiteľov</h2>
  <ol>
    <li>Po prihlásení máte možnosť nastaviť, ktoré sady úloh môžu študenti riešiť, koľko bodov získajú za každú sadu a kedy môžu byť riešené (dátum).</li>
    <li>Môžete sledovať tabuľku všetkých študentov, kde sú uvedené informácie o tom, koľko úloh si ktorý študent vygeneroval, koľko ich odovzdal a koľko bodov získal.</li>
    <li>Tabuľku je možné zoradiť kliknutím na stĺpce.</li>
    <li>Túto tabuľku môžete exportovať kliknutím na tlačidlo "Exportovať". Môžete si ju stiahnuť vo formáte PDF, CSV a iných formátoch.</li>
    <li>Máte tiež tabuľku s menami študentov a úloh, ktoré si vygenerovali, odovzdali a bodovali.</li>
  </ol>

  <h2>Zmena jazyka</h2>
  <ul>
    <li>Na stránke je možné kedykoľvek zmeniť jazyk kliknutím na malé tlačidlo pre výber jazyka, ktoré je umiestnené na pravom hornom rohu stránky.</li>
  </ul>

  <p>Poznámka: Toto je základné použitie webovej stránky. Môžu existovať ďalšie funkcie, ktoré nie sú uvedené v tomto návode. Ak máte ďalšie otázky alebo potrebujete viac informácií, neváhajte sa obrátiť na nás.</p>
</div>

<button class="big-button" onclick="downloadPDF()" style="font-size: 1rem;width: 150px;height: 50px; align-self: center">Stiahnuť ako PDF</button>

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

