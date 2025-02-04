<header>
    <!-- Menú deslizante -->
    <div id="sideMenu">
          <span class="close-btn" onclick="closeMenu()">&times;</span>
          <div class="username">
              <!-- Logo sobre el nombre -->
              <img src="https://i.ibb.co/Z6sx6B0T/Captura-de-pantalla-2025-02-04-144425-removebg-preview.png" alt="Logo" class="user-logo">
              <?php echo htmlspecialchars($nombreu); ?>
          </div>                          
            <?php if ($es_admin): ?>
            <a href="../panel_administrador/inicio.php"><i class="fa fa-home"></i> Inicio</a>
            <a href="../panel_administrador/inventario.php"> <i class="fas fa-boxes"></i> Inventario</a>
            <a href="../panel_administrador/configuracion.php"><i class="fa fa-users"></i> Administrar Usuarios/Sucursales</a>
            <a href="../panel_administrador/caja.php"><i class="fa fa-cash-register"></i> Vender</a>
            <a href="../panel_administrador/retiros.php"><i class="fas fa-wallet"></i> Retiros</a>
            <?php else: ?>
            <a href="../vendedor/caja_vendedor.php"><i class="fa fa-cash-register"></i> Vender</a>
            <?php endif; ?>

          <a href="../bd/logout.php"><i class="fas fa-power-off"></i> Cerrar Sesión</a>
    </div>

  <!-- Overlay -->
  <div id="menuOverlay" onclick="closeMenu()"></div>

  <div class="container mx-5">
      <button class="open-btn " onclick="openMenu()" data-bs-toggle="tooltip" title="Abrir menú"><i class="fas fa-bars"></i></button>
  </div>
</header>
<script>
    function openMenu() {
        document.getElementById("sideMenu").style.width = "250px";
        document.getElementById("menuOverlay").classList.add("active");
    }

    function closeMenu() {
        document.getElementById("sideMenu").style.width = "0";
        document.getElementById("menuOverlay").classList.remove("active");
    }
</script>