<?php
// src/views/partials/lang_toggle.php
?>
<style>
  /* Slide-in au hover */
  .lang-toggle-group:hover .lang-panel {
    transform: translateX(0);
  }
</style>

<div class="fixed top-[90%] left-0 transform -translate-y-1/2 z-50 lang-toggle-group">
  <button class="bg-white p-3 rounded-full shadow-lg hover:shadow-xl transition-shadow">🌐</button>
  <div class="lang-panel mt-2 bg-white rounded-lg shadow-lg
              transform -translate-x-full transition-transform duration-200
              whitespace-nowrap">
    <button onclick="setLang('en')" class="block px-4 py-2 hover:bg-gray-100">EN</button>
    <button onclick="setLang('fr')" class="block px-4 py-2 hover:bg-gray-100">FR</button>
  </div>
</div>

<!-- Widget Google Translate caché -->
<div id="google_translate_element" style="display:none;"></div>

<script>
  function googleTranslateElementInit() {
    new google.translate.TranslateElement({
      pageLanguage: 'fr',
      autoDisplay: false
    }, 'google_translate_element');
  }

  function setLang(lang) {
    document.cookie = 'googtrans=/fr/' + lang + ';path=/';
    document.cookie = 'googtrans=/fr/' + lang + ';path=/;domain=' + location.hostname;
    window.location.reload();
  }
</script>
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
