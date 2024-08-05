<footer id="footer">
   <div class="footer-body">
      <div class="container">
         <div class="row">
               <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12  pt-xl-5 pt-lg-5 pt-sm-3 pt-md-5 pt-3">
                  <a href="{{ url('/') }}" class="d-flex justify-content-center pb-3"><img src="{{asset('storage/'.$siteSetting->site_logo)}}" alt="" class="logo_img"></a>
                  <h6 class="text-white lh-base text-center mbls-view fw-normal">{{ $siteSetting->footer_content }}</h6>
               </div>
               <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12  pt-xl-5 pt-lg-5 pt-sm-4 pt-md-4 pt-5 locations">
                  <h6 class="text-white fw-bold pb-3">Contact US</h6>
                  <div class="d-flex gap-1 align-items-start pb-3">
                     <img src="{{asset('asset/home/location.png')}}" alt="location" class="mt-2">
                     <h6 class="text-white align-items-start lh-lg fw-normal">{{$siteSetting->address}}</h6>
                  </div>
                  <div class="d-flex gap-1 align-items-center pb-3">
                     <img src="{{asset('asset/home/mail.png')}}" alt="mail">
                     <a href="mailto:info@skyraan.com " class="text-white ms-2"><h6 class=" fw-normal">{{$siteSetting->mail_from_address}}</h6></a>
                  </div>
                  <div class="d-flex gap-1 align-items-center pb-3">
                     <img src="{{asset('asset/home/phone.png')}}" alt="phone">
                     <a href="tel:+91 78453 35332" class="text-white "><h6 class="fw-normal">+91 {{$siteSetting->phone}}</h6></a>
                  </div>
               </div>
               <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12 pt-xl-5 pt-lg-5 pt-sm-4 pt-md-4 pt-2 ps-xl-5 ps-lg-5 ps-sm-3 ps-md-3 ps-2 ">
                  <div class="sys-view">
                     <h6 class="text-white fw-bold pb-3">Information</h6>
                     <a href="{{url('/aboutus')}}"><h6 class="text-white  pb-3 fw-normal">About Us </h6></a>
                     <a href="{{url('/contactus')}}"><h6 class="text-white pb-3 fw-normal">Contact Us</h6></a>
                     <a href="{{url('/privacy-policy')}}"><h6 class="text-white pb-3 fw-normal">Privacy Policy</h6></a>
                     <a href="{{url('terms-and-condition')}}"><h6 class="text-white pb-3 fw-normal">Terms & Conditions</h6></a>
                  </div>
                  <div class="mbl-view">
                     <div class="d-flex align-items-center justify-content-between" id="for-drpt2">
                        <h6 class="fw-normal h-sms cursor text-white  fw-bold">Information</h6>
                        <div>
                           <img class="cursor down-ar" src="{{asset('asset/home/down-ar.svg')}}" alt="">
                           <img class="cursor up-ar" style="display:none;width: 14px;" src="{{asset('asset/home/up-ar.svg')}}" alt="">
                        </div>
                     </div>
                     <div class="fortr-drpt2" style="display:none">
                        <ul class="pt-2 ps-2">                           
                           <li class="text-white"><a href="{{url('/aboutus')}}"><h6 class="text-white  pb-3 fw-normal h-sms ">About Us </h6></a></li>
                           <li class="text-white"><a href="{{url('/contactus')}}"><h6 class="text-white pb-3 fw-normal h-sms">Contact Us</h6></a></li>
                           <li class="text-white"><a href="{{url('/privacy-policy')}}"><h6 class="text-white pb-3 fw-normal h-sms">Privacy Policy</h6></a></li>
                           <li class="text-white"><a href="{{url('/terms-and-condition')}}"><h6 class="text-white pb-3 fw-normal h-sms">Terms & Conditions</h6></a></li>
                        </ul>
                     </div>
                  </div>
               </div>
         </div>
      </div>
   </div>
   <button id="scroll-to-top"><i class="bi bi-arrow-up"></i></button>

   <div>
      <h6 class="text-center text-dark py-3 fw-bold copy-rights h-sm">All Rights Reserved @ 2024 Skyraa organics</h6>
   </div>
</footer>
