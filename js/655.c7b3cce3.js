"use strict";(self["webpackChunkeduardo_app"]=self["webpackChunkeduardo_app"]||[]).push([[655],{4037:function(e,t,a){a.d(t,{Z:function(){return w}});var s=a(3396),r=a(7139);const o=(0,s._)("div",{class:"text-center wow fadeInUp","data-wow-delay":"0.1s"},[(0,s._)("h6",{class:"section-title text-center text-primary text-uppercase"}," Feedbacks ")],-1),i={class:"container-xxl testimonial my-5 py-5 bg-dark wow zoomIn","data-wow-delay":"0.1s ",style:{"margin-bottom":"90px"}},n={class:"container"},l={class:"owl-carousel testimonial-carousel py-5"},d={class:"d-flex align-items-center"},c=(0,s._)("i",{class:"fas fa-user-circle fa-3x"},null,-1),u={class:"ps-3"},m={class:"fw-bold mb-1"},p={key:0,class:"alert alert-success mt-3",role:"alert"};function f(e,t,a,f,h,b){const v=(0,s.up)("Top");return(0,s.wg)(),(0,s.iD)(s.HY,null,[(0,s.Wm)(v),o,(0,s._)("div",i,[(0,s._)("div",n,[(0,s._)("div",l,[((0,s.wg)(!0),(0,s.iD)(s.HY,null,(0,s.Ko)(h.feed,(e=>((0,s.wg)(),(0,s.iD)("div",{key:h.name.id,class:"testimonial-item position-relative bg-white rounded overflow-hidden"},[(0,s._)("div",d,[c,(0,s._)("div",u,[(0,s._)("h6",m,(0,r.zw)(b.getName(e).name),1)])]),(0,s._)("p",null,(0,r.zw)(e.feedback),1)])))),128))]),h.feedbackSent?((0,s.wg)(),(0,s.iD)("div",p," Feedback sent successfully! ")):(0,s.kq)("",!0)])])],64)}var h=a(1738),b=a(1076),v={name:"testimonials",components:{Top:h.Z},data(){return{feed:[],name:[],feedbackSent:!1}},async mounted(){await this.getFeed()},methods:{async getFeed(){try{const[e,t]=await Promise.all([b.Z.get("/getFeedback"),b.Z.get("/getData")]);this.feed=e.data,this.name=t.data}catch(e){console.error("Error fetching data:",e)}},getName(e){return this.name.find((t=>t.id===e.id))||{}}}},g=a(89);const y=(0,g.Z)(v,[["render",f]]);var w=y},1655:function(e,t,a){a.r(t),a.d(t,{default:function(){return z}});var s=a(3396),r=a(9242);const o=(0,s.uE)('<div class="container-xxl bg-white p-0"><div class="service"><div class="container-fluid page-header mb-5 p-0"><div class="container-fluid page-header-inner py-5"><div class="container text-center pb-5"><h1 class="display-3 text-white mb-3 animated slideInDown"> Services </h1><nav aria-label="breadcrumb"><ol class="breadcrumb justify-content-center text-uppercase"><li class="breadcrumb-item"><a href="#"> Home </a></li><li class="breadcrumb-item"><a href="#"> Pages </a></li><li class="breadcrumb-item text-white active" aria-current="page"> contact_number </li></ol></nav></div></div></div></div></div><div class="container-xxl py-5"><div class="container"><div class="text-center wow fadeInUp" data-wow-delay="0.1s"><h6 class="section-title text-center text-primary text-uppercase"> Our Services </h6><h1 class="mb-5"> Explore Our <span class="text-primary text-uppercase"> Services </span></h1></div><div class="row g-4"><div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="0.1s"><a class="service-item rounded" href=""><div class="service-icon bg-transparent border rounded p-1"><div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center"><i class="fa fa-hotel fa-2x text-primary"></i></div></div><h5 class="mb-3"> Rooms &amp; Appartment </h5><p class="text-body mb-0"> &quot;Welcome to our luxurious and comfortable Rooms &amp; Apartments at Eduardo&#39;s Resort. Our carefully designed accommodations are the perfect haven for relaxation and rejuvenation. From spacious rooms with breathtaking views to well-appointed apartments that feel like a home away from home, we offer a range of options to suit your every need. &quot; </p></a></div><div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="0.2s"><a class="service-item rounded" href="/shop"><div class="service-icon bg-transparent border rounded p-1"><div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center"><i class="fa fa-utensils fa-2x text-primary"></i></div></div><h5 class="mb-3"> Food &amp; Restaurant </h5><p class="text-body mb-0"> &quot;Indulge in a culinary journey at our resort&#39;s Food &amp; Restaurant, where exceptional flavors and exquisite dining experiences await you. Our expert chefs artfully prepare a diverse range of dishes that tantalize your taste buds, from delectable international cuisine to locally inspired delicacies. &quot; </p></a></div></div><br><div class="row g-4"><div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="0.4s"><a class="service-item rounded" href="/swimminglesson"><div class="service-icon bg-transparent border rounded p-1"><div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center"><i class="fa fa-swimmer fa-2x text-primary"></i></div></div><h5 class="mb-3">Swimming Lesson</h5><p class="text-body mb-0"> &quot;Dive into a world of aqua adventures at our resort&#39;s Swimming Lessons! Whether you&#39;re a beginner looking to conquer the waters or an experienced swimmer seeking to refine your skills, our certified instructors are here to guide you through an exciting and educational journey.&quot; </p></a></div><div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="0.5s"><a class="service-item rounded" href=""><div class="service-icon bg-transparent border rounded p-1"><div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center"><i class="fa fa-glass-cheers fa-2x text-primary"></i></div></div><h5 class="mb-3"> Event &amp; Party </h5><p class="text-body mb-0"> &quot;Experience unforgettable moments at our resort&#39;s Events and Party offerings. Whether you&#39;re celebrating a special occasion, hosting a corporate event, or simply looking for a place to unwind with friends and family, our resort provides the perfect backdrop for your gatherings. &quot; </p></a></div></div></div></div>',2),i=(0,s.uE)('<div class="container newsletter mt-5 wow fadeIn" data-wow-delay="0.1s"><div class="row justify-content-center"><div class="col-lg-10 border rounded p-1"><div class="border rounded text-center p-1"><div class="bg-white rounded text-center p-5"><h4 class="mb-4"> Subscribe Our <span class="text-primary text-uppercase"> Newsletter </span></h4><div class="position-relative mx-auto" style="max-width:400px;"><input class="form-control w-100 py-3 ps-4 pe-5" type="text" placeholder="Enter your email"><button type="button" class="btn btn-primary py-2 px-3 position-absolute top-0 end-0 mt-2 me-2"> Submit </button></div></div></div></div></div></div>',1),n=(0,s._)("a",{href:"#",class:"btn btn-lg btn-primary btn-lg-square back-to-top"},[(0,s._)("i",{class:"bi bi-arrow-up"})],-1),l={class:"col-12"},d={key:0,class:"modal",tabindex:"-1",role:"dialog",style:{display:"block"}},c={class:"modal-dialog modal-lg"},u={class:"modal-content"},m={class:"modal-header"},p=(0,s._)("h5",{class:"modal-title"},"Enrollment Form",-1),f={class:"modal-body"},h={class:"form-group"},b=(0,s._)("label",{for:"fullname"},"Full Name:",-1),v={class:"form-group"},g=(0,s._)("label",{for:"contact_number"},"Contact Number:",-1),y={class:"form-group"},w=(0,s._)("label",{for:"age"},"Age:",-1),x={class:"form-group"},_=(0,s._)("label",{for:"experience"},"Swimming Experience:",-1),k={class:"form-group"},D=(0,s._)("label",{for:"lesson_date"},"Select Lesson Date:",-1),M=(0,s._)("option",{value:"",disabled:""},"Select a date",-1),S=(0,s._)("option",{value:"2024-03-17"},"March 17, 2024",-1),q=(0,s._)("option",{value:"2024-03-18"},"March 18, 2024",-1),F=[M,S,q],E=(0,s._)("br",null,null,-1),Z=(0,s._)("button",{type:"submit",class:"btn btn-primary"},"Submit",-1);function R(e,t,a,M,S,q){const R=(0,s.up)("Top"),I=(0,s.up)("spinner"),A=(0,s.up)("navbar"),N=(0,s.up)("feedbacks"),U=(0,s.up)("End"),O=(0,s.up)("Notification");return(0,s.wg)(),(0,s.iD)(s.HY,null,[(0,s.Wm)(R),(0,s.Wm)(I),(0,s.Wm)(A),o,(0,s.Wm)(N),i,(0,s.Wm)(U),n,(0,s._)("div",l,[e.isAddRoomModalOpen?((0,s.wg)(),(0,s.iD)("div",d,[(0,s._)("div",c,[(0,s._)("div",u,[(0,s._)("div",m,[p,(0,s._)("button",{type:"button",class:"close btn-danger",onClick:t[0]||(t[0]=(...e)=>q.closeAddRoomModal&&q.closeAddRoomModal(...e))},"×")]),(0,s._)("div",f,[(0,s._)("form",{class:"enrollment-form",onSubmit:t[6]||(t[6]=(0,r.iM)(((...e)=>q.save&&q.save(...e)),["prevent"]))},[(0,s._)("div",h,[b,(0,s.wy)((0,s._)("input",{type:"text",class:"form-control",id:"fullname","onUpdate:modelValue":t[1]||(t[1]=e=>S.fullName=e),required:""},null,512),[[r.nr,S.fullName]])]),(0,s._)("div",v,[g,(0,s.wy)((0,s._)("input",{type:"tel",class:"form-control",id:"contact_number","onUpdate:modelValue":t[2]||(t[2]=e=>S.contact_number=e),required:""},null,512),[[r.nr,S.contact_number]])]),(0,s._)("div",y,[w,(0,s.wy)((0,s._)("input",{type:"number",class:"form-control",id:"age","onUpdate:modelValue":t[3]||(t[3]=e=>S.age=e),required:""},null,512),[[r.nr,S.age]])]),(0,s._)("div",x,[_,(0,s.wy)((0,s._)("textarea",{class:"form-control",id:"experience","onUpdate:modelValue":t[4]||(t[4]=e=>S.experience=e),rows:"3",required:""},null,512),[[r.nr,S.experience]])]),(0,s._)("div",k,[D,(0,s.wy)((0,s._)("select",{class:"form-control",id:"lesson_date","onUpdate:modelValue":t[5]||(t[5]=e=>S.lessonDate=e),required:""},F,512),[[r.bM,S.lessonDate]])]),E,Z],32)])])])])):(0,s.kq)("",!0)]),(0,s.Wm)(O,{show:S.notification.show,type:S.notification.type,message:S.notification.message},null,8,["show","type","message"])],64)}var I=a(7516),A=(a(2166),a(1738)),N=a(5365),U=a(8693),O=a(4037),W=a(5429),j=a(9754),P=a(1076),T={name:"service",components:{spinner:W.Z,Top:A.Z,navbar:N.Z,End:U.Z,feedbacks:O.Z,Notification:j.Z},data(){return{notification:{show:!1,type:"",message:""},enrollmentMessage:"",successMessage:null,flatpickrInstance:null,selectedDate:null,dates:[],feed:[],fullName:"",contact_number:"",age:"",experience:"",lessonDate:"",swimming_date:"",enrollmentMessage:"",errorMessage:"",isSuccessMessage:!1}},mounted(){this.getDate()},created(){this.getDate()},methods:{openDatePicker(){this.flatpickrInstance.open()},async getDate(){try{const e=await P.Z.get("/getDate");e&&200===e.status&&Array.isArray(e.data)?(this.dates=e.data,this.initFlatpickr()):console.error("Error fetching dates: Response data is empty or not an array")}catch(e){console.error("Error fetching dates:",e)}},initFlatpickr(){this.dates?this.flatpickrInstance=(0,I.Z)(this.$refs.datepicker,{dateFormat:"Y-m-d",enable:this.dates,onClose:e=>{this.selectedDate=e[0]},onReady:(e,t,a)=>{e.forEach((e=>{const t=a.days.querySelector(`[data-date="${e}"]`);t&&t.classList.add("custom-selected-date")}))}}):console.error("Dates array is null or undefined")},closeAddRoomModal(){this.isAddRoomModalOpen=!1},async save(){try{const e=sessionStorage.getItem("id"),t=await P.Z.post("enroll",{id:e,fullName:this.fullName,contact_number:this.contact_number,age:this.age,experience:this.experience,lesson_date:this.formatDate(this.selectedDate)});200===t.status&&(this.enrollmentMessage="Successfully enrolled",this.isSuccessMessage=!0,this.clearFormFields(),setTimeout((()=>{this.enrollmentMessage="",this.isSuccessMessage=!1}),2e3))}catch(e){console.error("Error submit",e),e.response&&400===e.response.status?this.errorMessage=e.response.data.message||"Submit failed":this.errorMessage="Error submit",this.enrollmentMessage=""}},clearFormFields(){this.fullName="",this.contact_number="",this.age="",this.experience="",this.selectedDate=""},formatDate(e){return e.toISOString().split("T")[0]},async getFeed(){const[e,t]=await Promise.all([P.Z.get("/getFeedback"),P.Z.get("/getData")]);this.feed=e.data,this.name=t.data},getName(e){return this.name.find((t=>t.id===e.id))||{}},openAddRoomModal(){this.isAddRoomModalOpen=!0},closeAddRoomModal(){this.isAddRoomModalOpen=!1}}},C=a(89);const V=(0,C.Z)(T,[["render",R]]);var z=V}}]);
//# sourceMappingURL=655.c7b3cce3.js.map