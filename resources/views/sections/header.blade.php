<header style=""  class="ur_header_section primary-bg-color header-sticky">
    {{-- <div class="topbar py-2 bottom-border d-none d-lg-block">
      <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-xl-7 col-lg-8">
                <div class="topbar-info d-flex align-items-center gap-48 flex-wrap">
                  <span class="text-white item-single">
                    <i class="fa fa-phone"></i>
                    <a href="tel:1234567890" class="text-white">+91-1234567890</a> (7AM - 7PM) </span>
                  <span class="text-white item-single">
                    <i class="fa fa-basket-shopping"></i> Free Shipping On Orders </span>
                  <span class="text-white item-single">
                    <i class="fa fa-bank"></i> Bank Offers </span>
                </div>
              </div>
        
        </div>
      </div>
    </div> --}}
    <div class="header-wrapper">
      <div class="container-fluid">
        <div class="row align-items-center">
          <div class="col-xl-2 col-6">
            <a href="/" class="logo-white">
              <img style="width:70%; " src="assets/img/logo_argen.jpg" alt="logo" class="img-fluid">
            </a>
          </div>
          <div class="col-xxl-7 col-xl-8 d-none d-xl-block">
            <nav class="ur-navmenu">
              <ul>
                <li>
                    <a href="/">Accueil</a>
                  </li>
                
                
                <li>
                  <a href="/boutique">Boutique réseau</a>
                </li>

                <li>
                  <a href="/nouveaute">Nouveauté</a>
                </li>
                
                <li>
                  <a href="/a-propos">A Propos</a>
                </li>
                <li>
                    <a href="/contact">Contactez-nous</a>
                  </li>
              </ul>
            </nav>
          </div>
          <div class="col-xxl-3 col-xl-2 col-6">
            <div class="ur-header-right d-flex align-items-center justify-content-end">
              <div class="ur-user-links position-relative">
                <button type="button" class="user-btn">
                  <i class="fa-regular fa-user me-5"></i>
                </button>
                <ul class="position-absolute  user-menu">
                  <li>
                    <a href="{{ route('login') }}">S'inscrire</a>
                  </li>
                
                  </li>
                </ul>
              </div>
              
              <div class="d-sm-none">
                <a href="/panier" class="header-link header-search-open">
                  <i style="color: #fff !important" class="fa-solid fa-basket-shopping"></i>

                </a>
              </div>
              <div class="header-toggle">
                <a href="/panier" type="button" class="wa-header-toggle offcanvus-toggle d-none d-xl-inline-block">
                  {{-- <span></span>
                  <span></span>
                  <span></span> --}}
                  <i style="color: #fff !important" class="fa-solid fa-basket-shopping"></i>
                </a>
                <button type="button" class="wa-header-toggle mobile-menu-toggle d-xl-none">
                  <span></span>
                  <span></span>
                  <span></span>
                  
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>