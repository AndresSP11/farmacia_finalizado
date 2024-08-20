<?php
header("X-Frame-Options: DENY");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./styles/nosotros.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../styles/navbar.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <div class="navbar" id="navbar">
        <button class="navbar-toggle" id="navbar-toggle">&#9660;</button>
        <div class="contenedor-navbar">
        <div class="navbar-element left">
        <a href="/inicio.php"><i class="fas fa-home icono" style="display:flex"></i></a>
        </div>
        <div class="navbar-links" id="navbar-links">
            <a href="/inicio.php">Inicio</a>
            <a href="/nosotros.php">Nosotros</a>
            <a href="/nosotros.php#doctora">Contactácnos</a>
        </div>
        </div>
    </div>
    <div class="navbar-element-2 right">
    <button id="scroll-to-top" class="scroll-button">
        <i class="fa-solid fa-hand-point-up icono" style="display:flex"></i>
    </button>
    </div>
    <section class="doctor-img" id="doctor-img">
        <img src="./img/center-med.png" alt="">
        <div class="container-bot">

        </div>
    
    </section>

    <!-- NOSOSTROS -->
    <section class="nosotros-contain" id="nosotros-contain">

        <div class="absolu-1">
            <a href="#nosotros-contain">
                <i class='bx bx-plus-medical'></i>
                <p>Nosotros</p>
            </a>
        </div>
        <div class="absolu-2">
            <a href="#mision-dm">
                <i class='bx bxs-heart' ></i>
                <p>Misión y <br>
                visión</p>
            </a>
        </div>
        <div class="absolu-3">
            <a href="#doctora">
                <i class='bx bxs-capsule'></i>
                <p>
                    Contacto
                </p>
            </a>
        </div>


        <div class="contain-child-nos">
            <img src="./img/enfermeros-group.png" alt="">
            <div class="text-contain-enfermeros">
                <h1>Nosotros</h1>
                <br>
                <br>
                <p>La Universidad Nacional de Ingeniería realizó la ceremonia de <br>
                    entrega de obra y develación de la placa del Centro Médico UNI el<br>
                    29 de marzo del 2022, en la cual reafirmaron su compromiso con la<br>
                    salud y el bienestar de la comunidad universitaria, mejorando la<br>
                    capacidad y calidad en la atención y asistencia médica.</p>
                <br>
                <br>
                    <p>
                    El Centro Médico UNI abarca un área total de 2 mil 489.97 m2, que <br>
                    cuenta con los principales servicios y ambientes como Consultorio <br>
                    General; Odontología; Psicología; Área de Medicina Física y <br>
                    Rehabilitación; así como Salas de Rayos X, Ecografía, y Endoscopia.
                </p>
                <br>
                <br>
                <p>
                    Para la moderna infraestructura, así como su equipamiento y<br>
                    mobiliario, se tuvo una inversión de S/. 8'356,202.00, la cual cubrirá <br>
                    las necesidades de los estudiantes, docentes, personal <br>
                    administrativo y operativo de nuestra universidad, cumpliendo <br>
                    adecuadamente con los servicios y requerimientos de un <br>
                    Establecimiento de Salud Tipo 1-3. 
                </p>
            </div>
        </div>
    </section>
    <section class="mision-dm" id="mision-dm">
        <div class="texto-mision">
            <h1>Misión</h1>
            <p>Velar por la salud integral y el desarrollo humano <br>
            de los miembros de la comunidad universitaria<br> UNI,mediante
            la prestación de servicios de salud<br>
             integral y orientación psicológica que respondan<br>
            a sus necesidades y expectativas, con un equipo<br>humano competente, ética y empático </p>
        </div>
    </section>
    <section class="doctor-right" id="doctor-right">
        <img src="./img/logo-uni-plomo.png" alt="">
    </section>
    <section class="herramienta-doctor" id="herramienta-doctor">
        <div class="herramienta-cont">
            <h1>Visión</h1>
            <p>
                Ser una unidad que brinda servicios de salud <br>
                integral y orientación psicológica de alta <br>
                calidad a la comunidad universitaria, con un <br>
                enfoque holístico, participativo y sostenible.
            </p>
        </div>
    </section>
    <section class="doctora" id="doctora">
        <div class="doctora-container">
            <div class="contenedor-imagen-doc">
                <img src="./img/doctora-mujer.png" alt="">
            </div>
            <div class="doc-cont-children">
                <h2>Contacto</h2>
                <div class="flex-unit caja-doc-white">
                    <i class='bx bxs-phone'></i>
                    <p>Anexo 3001 - UCM</p>
                </div>
                <div class="flex-unit caja-doc-black">
                    <i class='bx bxs-phone'></i>
                    <p>Anexo 3018 - FARMACIA</p>
                </div>
                <div class="flex-unit caja-doc-white">
                    <i class='bx bxl-facebook'></i>
                    <p>UNIDAD DE CENTRO MÉDICO</p>
                </div>
                <div class="flex-unit caja-doc-black">
                    <i class='bx bxl-instagram' ></i>
                    <p>UNIDAD DE CENTRO MÉDICO</p>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="contenedor-fin">
            <img src="./img/uni-left-f.png" alt="">
            <img src="./img/uni-central-f.png" alt="">
            <img src="./img/uni-right-f.png" alt="">
        </div>
    </footer>
    <script src="../js/navbar.js"></script>

</body>
</html>