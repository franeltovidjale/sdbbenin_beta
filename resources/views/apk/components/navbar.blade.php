
<aside style="background: black !important" class="main-sidebar sidebar-dark-primary elevation-4">
   <!-- Brand Logo -->
   <center>
      <a href="#" class="brand-link m-auto">
         <img style="width:80%;height:100px;margin-left:-3%;object-fit:cover" src="{{ asset('assets/img/logoblanc.png') }}" alt="logo" class="img-fluid">
      </a>
   </center>
   <!-- Sidebar -->
   <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
         <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Onglets accessibles à tous les utilisateurs -->
            <li class="nav-item">
               <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.index') ? 'active' : '' }}">
                  <p style="white-space: nowrap;"><i class="nav-icon fas fa-chart-line"></i> Rapport des Ventes</p>
               </a>
            </li>
            
            <li class="nav-item">
               <a href="{{ route('purchases.index') }}" class="nav-link {{ request()->routeIs('purchases.index') ? 'active' : '' }}">
                  <p style="white-space: nowrap;"><i class="nav-icon fas fa-chart-bar"></i> Rapport des Achats</p>
               </a>
            </li>
            
            <li class="nav-item">
               <a href="{{ route('achatvente') }}" class="nav-link {{ request()->routeIs('achatvente') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-exchange-alt"></i>
                  <p style="white-space: nowrap;">Achats/Ventes</p>
               </a>
            </li>
            
            <li class="nav-item">
               <a href="{{ route('adminlistarticles') }}" class="nav-link {{ request()->routeIs('adminlistarticles') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-clipboard-list"></i>
                  <p>Liste des Articles</p>
               </a>
            </li>
            
            <li class="nav-item">
               <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.index') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-tags"></i>
                  <p>Catégorie Article</p>
               </a>
            </li>
            
            <!-- Onglets accessibles uniquement aux administrateurs -->
            @if(auth()->user()->is_admin)
            <li class="nav-header">Espace Admin</li>
            
            <li class="nav-item">
               <a href="{{ route('ajoutarticle') }}" class="nav-link {{ request()->routeIs('ajoutarticle') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-plus-circle"></i>
                  <p>Ajouter Article</p>
               </a>
            </li>

            <li class="nav-header">Gestion utilisateurs</li>
            
            <li class="nav-item">
               <a href="{{ route('invitation-codes.index') }}" class="nav-link {{ request()->routeIs('invitation-codes.index') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user-plus"></i>
                  <p>Créer Utilisateur</p>
               </a>
            </li>
            
            <li class="nav-item">
               <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-users"></i>
                  <p>Utilisateurs</p>
               </a>
            </li>
            @endif
         </ul>
      </nav>
      <!-- /.sidebar-menu -->
   </div>
   <!-- /.sidebar -->
</aside>

<style>
   .active{
      background-color: #4A90E2 !important;
   }
   .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active, .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active {
    background-color: #4A90E2;
    color: white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

/* Animation sur les icônes */
.nav-icon {
    transition: transform 0.2s ease;
}

.nav-link:hover .nav-icon {
    transform: scale(1.2);
}

/* Effets de transition sur les liens */
.nav-link {
    transition: all 0.3s ease;
    border-radius: 4px;
    margin: 3px 8px;
}

.nav-link:hover {
    padding-left: 25px;
}

/* Style des en-têtes */
.nav-header {
    color: #b8c7ce;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    margin-top: 15px;
    padding-left: 15px;
}
</style>