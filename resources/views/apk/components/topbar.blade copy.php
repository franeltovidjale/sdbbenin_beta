<nav style="background:#000;color: #FFF !important;padding:20px 0" class="main-header navbar navbar-expand  navbar-light">
    <!-- Left navbar links -->
    
    <ul class="navbar-nav">
       <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      
    </ul>
    <marquee behavior=""  class="h4  " direction="">GESTION DU STOCK DE KMJ APPLE STORE</marquee>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
       <!-- Navbar Search -->
     
       <!-- Messages Dropdown Menu -->
       <li class="nav-item dropdown">
          
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
             <a href="#" class="dropdown-item">
                <!-- Message Start -->
                <div class="media">
                   {{-- <img src="dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle"> --}}
                   <div class="media-body">
                      <h3 class="dropdown-item-title">
                         Brad Diesel
                         <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                      </h3>
                      <p class="text-sm">Call me whenever you can...</p>
                      <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                   </div>
                </div>
                <!-- Message End -->
             </a>
             <div class="dropdown-divider"></div>
             <a href="#" class="dropdown-item">
                <!-- Message Start -->
                <div class="media">
                   <img src="dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                   <div class="media-body">
                      <h3 class="dropdown-item-title">
                         John Pierce
                         <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                      </h3>
                      <p class="text-sm">I got your message bro</p>
                      <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                   </div>
                </div>
                <!-- Message End -->
             </a>
             <div class="dropdown-divider"></div>
             <a href="#" class="dropdown-item">
                <!-- Message Start -->
                <div class="media">
                   <img src="dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                   <div class="media-body">
                      <h3 class="dropdown-item-title">
                         Nora Silvester
                         <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                      </h3>
                      <p class="text-sm">The subject goes here</p>
                      <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                   </div>
                </div>
                <!-- Message End -->
             </a>
             <div class="dropdown-divider"></div>
             <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
          </div>
       </li>
       <!-- Notifications Dropdown Menu -->
      
       <li class="nav-item">
         <form method="POST" action="{{ route('logout') }}" class="d-inline">
             @csrf
             <button type="submit" class="nav-link btn btn-link">
                 <i class="fas fa-sign-out-alt"></i>
             </button>
         </form>
      </li>
     
    </ul>
 </nav>

 <style>
   .navbar-light .navbar-nav .nav-link {
    color: #FFF !important;
}
 </style>