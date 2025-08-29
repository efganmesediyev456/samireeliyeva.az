 <!-- ========== Left Sidebar Start ========== -->
 <div class="vertical-menu">

     <div data-simplebar class="h-100">

         <!--- Sidemenu -->
         <div id="sidebar-menu">
             <!-- Left Menu Start -->
             <ul class="metismenu list-unstyled" id="side-menu">


                 <li>
                     <a href="{{ route('admin.users.index') }}" class="waves-effect">
                         <i class="bx bx-user"></i>
                         <span key="t-chat">İstifadəçilər</span>
                     </a>
                 </li>

                <li>
                     <a href="{{ route('admin.translations.index') }}" class="waves-effect">
                         <i class="bx bx-user"></i>
                         <span key="t-chat">Tərcümələr</span>
                     </a>
                 </li>


                 <li>
                     <a href="javascript: void(0);" class="has-arrow waves-effect">
                         <i class="bx bx-file"></i> {{-- Statik səhifələr üçün uyğun ikon --}}
                         <span key="t-dashboards">Statik səhifələr</span>
                     </a>
                     <ul class="sub-menu" aria-expanded="false">
                         <li>
                             <a href="{{ route('admin.terms-and-conditions.index') }}" class="waves-effect">
                                 <i class="bx bx-shield-quarter"></i> {{-- Qaydalar və şərtlər --}}
                                 <span key="t-chat">Qaydalar və şərtlər</span>
                             </a>
                         </li>
                         <li>
                             <a href="{{ route('admin.about.index') }}" class="waves-effect">
                                 <i class="bx bx-info-circle"></i> {{-- Haqqımızda --}}
                                 <span key="t-chat">Haqqımızda</span>
                             </a>
                         </li>
                         <li>
                             <a href="{{ route('admin.exam_banner.index') }}" class="waves-effect">
                                 <i class="bx bx-image-alt"></i> {{-- İmtahan banneri --}}
                                 <span key="t-chat">İmtahan Banneri</span>
                             </a>
                         </li>
                         <li>
                             <a href="{{ route('admin.textbook_banner.index') }}" class="waves-effect">
                                 <i class="bx bx-book-content"></i> {{-- Dərsliklər və test bankları --}}
                                 <span key="t-chat">Dərsliklər və test bankları banneri</span>
                             </a>
                         </li>
                     </ul>
                 </li>



                 <li>
                     <a href="javascript: void(0);" class="has-arrow waves-effect">
                         <i class="bx bx-home-circle"></i> {{-- Ana səhifə üçün uyğun ikon --}}
                         <span key="t-dashboards">Ana səhifə</span>
                     </a>
                     <ul class="sub-menu" aria-expanded="false">
                         <li>
                             <a href="{{ route('admin.free_online_lessons.index') }}" class="waves-effect">
                                 <i class="bx bx-play-circle"></i> {{-- Onlayn dərslər üçün uyğun ikon --}}
                                 <span key="t-chat">Pulsuz Onlayn Dərslər</span>
                             </a>
                         </li>

                         <li>
                             <a href="{{ route('admin.important_links.index') }}" class="waves-effect">
                                 <i class="bx bx-link-alt"></i> {{-- Keçidlər üçün link ikonu --}}
                                 <span key="t-chat">Mühüm keçidlər</span>
                             </a>
                         </li>

                         <li>
                             <a href="{{ route('admin.educational_regions.index') }}" class="waves-effect">
                                 <i class="bx bx-map"></i> {{-- Regionlar üçün xəritə ikonu --}}
                                 <span key="t-chat">Təhsil Regionları</span>
                             </a>
                         </li>

                         <li>
                             <a href="{{ route('admin.partners.index') }}" class="waves-effect">
                                 <i class="bx bx-group"></i> {{-- Tərəfdaşlar üçün qrup ikonu --}}
                                 <span key="t-chat">Tərəfdaşlar</span>
                             </a>
                         </li>
                     </ul>
                 </li>



                 <li>
                     <a href="javascript: void(0);" class="has-arrow waves-effect">
                         <i class="bx bx-message-square-dots"></i> {{-- Forumlar üçün uyğun ikon --}}
                         <span key="t-dashboards">Forumlar</span>
                     </a>
                     <ul class="sub-menu" aria-expanded="false">
                         <li>
                             <a href="{{ route('admin.have_questions.index') }}" class="waves-effect">
                                 <i class="bx bx-help-circle"></i> {{-- Sual üçün uyğun ikon --}}
                                 <span key="t-chat">Sualın var?</span>
                             </a>
                         </li>

                         <li>
                             <a href="{{ route('admin.website_likes.index') }}" class="waves-effect">
                                 <i class="bx bx-like"></i> {{-- Reytinqlər üçün uyğun ikon --}}
                                 <span key="t-chat">Vebsayt Reytinqləri</span>
                             </a>
                         </li>
                     </ul>
                 </li>



                 <li>
                     <a href="{{ route('admin.orders.index') }}" class="waves-effect">
                         <i class="bx bx-receipt"></i> {{-- Sifariş üçün uyğun ikon --}}
                         <span key="t-chat">Sifarişlər</span>
                     </a>
                 </li>

                 <li>
                     <a href="{{ route('admin.blognews.index') }}" class="waves-effect">
                         <i class="bx bx-news"></i> {{-- Xəbərlər üçün uyğun ikon --}}
                         <span key="t-chat">Xəbərlər və yeniliklər</span>
                     </a>
                 </li>

                 <li>
                     <a href="{{ route('admin.galleryphotos.index') }}" class="waves-effect">
                         <i class="bx bx-image"></i> {{-- Şəkillər üçün ikon --}}
                         <span key="t-chat">Qalereya şəkillər</span>
                     </a>
                 </li>

                 <li>
                     <a href="{{ route('admin.gallery-videos.index') }}" class="waves-effect">
                         <i class="bx bx-video"></i> {{-- Videolar üçün ikon --}}
                         <span key="t-chat">Qalereya videolar</span>
                     </a>
                 </li>

                 <li>
                     <a href="{{ route('admin.advertisements.index') }}" class="waves-effect">
                         <i class="fas fa-bullhorn fs-5"></i> {{-- Reklamlar üçün FontAwesome ikon --}}
                         <span key="t-chat">Elanlar</span>
                     </a>
                 </li>






                 <li>
                     <a href="{{ route('admin.services.index') }}" class="waves-effect">
                         <i class="bx bx-cog"></i> {{-- Xidmətlər üçün uyğun ikon --}}
                         <span key="t-chat">Xidmətlər</span>
                     </a>
                 </li>

                 <li>
                     <a href="{{ route('admin.textbooks.index') }}" class="waves-effect">
                         <i class="bx bx-book"></i> {{-- Dərsliklər üçün uyğun ikon --}}
                         <span key="t-chat">Dərsliklər</span>
                     </a>
                 </li>

                 <li>
                     <a href="{{ route('admin.categories.index') }}" class="waves-effect">
                         <i class="fas fa-th-large fs-5"></i> {{-- Kateqoriyalar üçün FontAwesome ikon --}}
                         <span key="t-chat">Kateqoriyalar</span>
                     </a>
                 </li>


                 <li>
                     <a href="{{ route('admin.subcategories.index') }}" class="waves-effect">
                         <i class="bx bx-grid-alt"></i> {{-- Alt kateqoriyalar üçün uyğun ikon --}}
                         <span key="t-chat">Alt Kateqoriyalar</span>
                     </a>
                 </li>











                 <li>
                     <a href="{{ route('admin.settings.index') }}" class="waves-effect">
                         <i class="bx bx-slider-alt"></i> {{-- Sayt parametrləri üçün uyğun ikon --}}
                         <span key="t-chat">Sayt Parametrləri</span>
                     </a>
                 </li>

                 <li>
                     <a href="{{ route('admin.social_links.index') }}" class="waves-effect">
                         <i class="bx bx-share-alt"></i> {{-- Sosial bağlantılar üçün uyğun ikon --}}
                         <span key="t-chat">Sosial Bağlantılar</span>
                     </a>
                 </li>

                 <li>
                     <a href="{{ route('admin.vacancy-share-socials.index') }}" class="waves-effect">
                         <i class="bx bx-share"></i> {{-- Sosial paylaşım üçün ikon --}}
                         <span key="t-chat">Səhifə Paylaşım Sosial</span>
                     </a>
                 </li>










             </ul>
         </div>
         <!-- Sidebar -->
     </div>
 </div>
 <!-- Left Sidebar End -->