<template>
    <footer class="footer-gradient text-black q-pa-lg">
      <div class="row items-start justify-evenly q-col-gutter-lg text-center">
  
        <!-- LOGO + REDES -->
        <div class="col-12 col-md-3 column items-center justify-center" style="margin-top: 55px;">
          <q-avatar size="70px" class="bg-white q-mb-md">
            <img src="/public/icons/logoDyd.jpg" alt="DYD Accesorios" />
          </q-avatar>
  
          <div class="row justify-center q-gutter-sm">
            <q-btn
              flat
              round
              dense
              icon="mdi-instagram"
              color="black"
              href="https://www.instagram.com/dydaccesorios.ok/?hl=es"
              target="_blank"
            />
    
          </div>
        </div>
  

        <!-- LOCALES -->
        <div class="col-12 col-md-3 column items-center justify-center" style="text-align: justify;">
          <h6 class="text-weight-bold q-mb-sm">Locales</h6>
          <ul class="no-padding no-margin text-caption">
            <li> <q-icon name="mdi-map-marker" color="black" size="20px" />Tte. Gral. Juan Domingo Perón 2450 / Local 46 - CABA  </li>
            <li> <q-icon name="mdi-map-marker" color="black" size="20px" />Tte. Gral. Juan Domingo Perón 2450 / Local 17 - CABA  </li>
            <li> <q-icon name="mdi-map-marker" color="black" size="20px" />Larrea 175 - CABA </li>
            <li> <q-icon name="mdi-map-marker" color="black" size="20px" />Av. Corrientes 2348 - CABA </li>
          </ul>
        </div>
  
  
        <!-- WHATSAPP -->
        <div class="col-12 col-md-3 column items-center justify-center">
          <h6 class="text-weight-bold q-mb-sm">Consultas</h6>
          <q-btn
            round
            color="green"
            icon="mdi-whatsapp"
            size="lg"
            href="https://wa.me/5491150016367?text=Hola!%20Quisiera%20hacer%20una%20consulta%20sobre%20un%20producto"
            target="_blank"
          />
          <p class="text-caption q-mt-sm">
            Escribinos por WhatsApp para atención personalizada
          </p>
        </div>
  
      </div>
  
      <!-- Línea inferior -->
      <div class="text-center text-grey-5 text-caption" style="margin-top: 15px;">
        © 2025 DYD Accesorios — Todos los derechos reservados
      </div>
    </footer>
  </template>
  
  <script setup>
  </script>
  
  <style scoped>

    .footer-gradient {
      background: linear-gradient(
        180deg,              
        #ffffff 40%,           
        #bd608f 100%            
        /* #ffffff 40%,           
        #c1066473 100%    */
      );
    }
    .q-footer{
        padding: 10px 20px;
    }

    ul {
      list-style-type: none;
    }
  

  
  </style>
  