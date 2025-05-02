<nav style="background:#000;color: #FFF !important;padding:20px 0" class="main-header navbar navbar-expand navbar-light">
   <!-- Left navbar links -->
   <ul class="navbar-nav">
      <li class="nav-item">
         <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
   </ul>
   <marquee behavior="" class="h4" direction="">GESTION DU STOCK DE KMJ APPLE STORE</marquee>
   <!-- Right navbar links -->
   <ul class="navbar-nav ml-auto">
      <!-- Profil utilisateur -->
      <li class="nav-item dropdown">
         <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{-- <i style="font-size: 25px;" class="fas fa-user-circle mr-1"></i> {{ Auth::user()->name }} --}}
         </a>
         <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ route('profile.edit') }}">
               <i class="fas fa-user mr-2"></i> Mon profil
            </a>
            <div class="dropdown-divider"></div>
            <form method="POST" action="{{ route('logout') }}" class="dropdown-item p-0">
               @csrf
               <button type="submit" class="btn btn-link text-danger w-100 text-left px-3 py-2">
                  <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
               </button>
            </form>
         </div>
      </li>
   </ul>
</nav>

<style>
  /* Styles pour la navbar */
  .navbar-light .navbar-nav .nav-link {
     color: #FFF !important;
     font-weight: 500;
     transition: all 0.3s ease;
  }
  
  .navbar-light .navbar-nav .nav-link:hover {
     color: #f8f9fa !important;
     opacity: 0.8;
  }
  
  /* Styles pour le dropdown */
  .dropdown-menu {
     border-radius: 0.25rem;
     box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
     border: none;
     padding: 0.5rem 0;
  }
  
  .dropdown-item {
     padding: 0.5rem 1.5rem;
     font-size: 0.9rem;
  }
  
  .dropdown-item:hover {
     background-color: #f8f9fa;
  }
  
  /* Style pour le bouton de déconnexion */
  .dropdown-item .btn-link {
     text-decoration: none;
     font-weight: 500;
  }
  
  /* Animation pour le menu déroulant */
  .dropdown-menu {
     animation: fadeIn 0.2s ease-in-out;
  }
  
  @keyframes fadeIn {
     from { opacity: 0; transform: translateY(-10px); }
     to { opacity: 1; transform: translateY(0); }
  }
  
  /* Ajustement pour le marquee */
  marquee {
     font-weight: 600;
     letter-spacing: 0.5px;
  }
  
  /* Responsive adjustments */
  @media (max-width: 768px) {
     marquee {
        max-width: 40%;
     }
  }
</style>