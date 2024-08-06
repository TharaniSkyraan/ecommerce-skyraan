<footer id="footer">
   <div class="footer-body">
      <div class="container">
         <div class="row">
               <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12  pt-xl-5 pt-lg-5 pt-sm-3 pt-md-5 pt-3">
                  <a href="{{ url('/') }}" class="d-flex justify-content-center pb-3"><img src="{{asset('storage/'.$siteSetting->site_logo)}}" alt="" class="logo_img"></a>
                  <h6 class="text-white lh-base text-start fw-normal foo-des">{{ $siteSetting->footer_content }}</h6>
               </div>
               <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12  pt-xl-5 pt-lg-5 pt-sm-4 pt-md-4 pt-5 locations  ps-xl-5 ps-lg-5 ps-sm-3 ps-md-3 ps-2 ">
                  <span class="text-white fw-bold pb-3 foo-head">Contact US</span>
                  <hr class="w-50 my-1 border-white">
                  <div class="d-flex gap-1 align-items-start pb-3 pt-2">
                     <i class="bi bi-geo-alt mt-1 text-white"></i>
                     <h6 class="text-white align-items-start lh-lg fw-normal foo-des">{{$siteSetting->address}}</h6>
                  </div>
                  <div class="d-flex gap-1 align-items-center pb-3">
                     <i class="bi bi-envelope text-white"></i>
                     <a href="mailto:info@skyraan.com " class="text-white ms-xl-2 ms-lg-2 ms-sm-2 ms-md-2 ms-0"><h6 class="foo-des fw-normal">{{$siteSetting->mail_from_address}}</h6></a>
                  </div>
                  <div class="d-flex gap-1 align-items-center pb-3">
                     <i class="bi bi-telephone text-white"></i>
                     <a href="tel:+91 78453 35332" class="text-white "><h6 class="foo-des fw-normal">+91 {{$siteSetting->phone}}</h6></a>
                  </div>
               </div>
               <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12 pt-xl-5 pt-lg-5 pt-sm-4 pt-md-4 pt-2">
                  <div class="sys-view">
                     <span class="text-white fw-bold pb-3 foo-head">Information</span>
                     <hr class="w-50 my-1 border-white">
                     <div class="d-flex gap-2 pb-3 pt-2"><img src="{{asset('asset/home/arrow_1.svg')}}" alt="" class="png-foo"><a href="{{url('/')}}"><h6 class="text-white  fw-normal foo-des">Home</h6></a></div>
                     <div class="d-flex gap-2 pb-3"><img src="{{asset('asset/home/arrow_1.svg')}}" alt="" class="png-foo"><a href="{{url('/aboutus')}}"><h6 class="text-white  fw-normal foo-des">About Us </h6></a></div>
                     <div class="d-flex gap-2 pb-3"><img src="{{asset('asset/home/arrow_1.svg')}}" alt="" class="png-foo"><a href="{{url('/contactus')}}"><h6 class="text-white fw-normal foo-des">Contact Us</h6></a></div>
                     <div class="d-flex gap-2 pb-3"><img src="{{asset('asset/home/arrow_1.svg')}}" alt="" class="png-foo"><a href="{{url('/privacy-policy')}}"><h6 class="text-white fw-normal foo-des">Privacy Policy</h6></a></div>
                     <div class="d-flex gap-2 pb-3"><img src="{{asset('asset/home/arrow_1.svg')}}" alt="" class="png-foo"><a href="{{url('terms-and-condition')}}"><h6 class="text-white fw-normal foo-des">Terms & Conditions</h6></a></div>
                  </div>
                  <div class="mbl-view">
                     <div class="d-flex align-items-center justify-content-between" id="for-drpt2">
                        <span class="fw-normal cursor text-white fw-bold foo-head ">Information</span>
                        <div>
                           <img class="cursor down-ar" src="{{asset('asset/home/down-ar.svg')}}" alt="">
                           <img class="cursor up-ar" style="display:none;width: 14px;" src="{{asset('asset/home/up-ar.svg')}}" alt="">
                        </div>
                     </div>
                     <hr class="w-50 my-1 border-white">

                     <div class="fortr-drpt2">
                        <ul class="pt-2 ps-3">                           
                           <li class="text-white d-flex pb-3 gap-2"><img src="{{asset('asset/home/arrow_1.svg')}}" alt="" class="png-foo"><a href="{{url('/aboutus')}}"><h6 class="text-white  fw-normal h-sms ">About Us </h6></a></li>
                           <li class="text-white d-flex pb-3 gap-2"><img src="{{asset('asset/home/arrow_1.svg')}}" alt="" class="png-foo"><a href="{{url('/contactus')}}"><h6 class="text-white fw-normal h-sms">Contact Us</h6></a></li>
                           <li class="text-white d-flex pb-3 gap-2"><img src="{{asset('asset/home/arrow_1.svg')}}" alt="" class="png-foo"><a href="{{url('/privacy-policy')}}"><h6 class="text-white fw-normal h-sms">Privacy Policy</h6></a></li>
                           <li class="text-white d-flex pb-xl-3 pb-lg-3 pb-md-3 pb-sm-3 pb-0 gap-2"><img src="{{asset('asset/home/arrow_1.svg')}}" alt="" class="png-foo"><a href="{{url('/terms-and-condition')}}"><h6 class="text-white fw-normal h-sms">Terms & Conditions</h6></a></li>
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
