"use strict";(self["webpackChunkeduardo_app"]=self["webpackChunkeduardo_app"]||[]).push([[131],{4037:function(t,a,e){e.d(a,{Z:function(){return y}});var s=e(3396),i=e(7139);const n=(0,s._)("div",{class:"text-center wow fadeInUp","data-wow-delay":"0.1s"},[(0,s._)("h6",{class:"section-title text-center text-primary text-uppercase"}," Feedbacks ")],-1),c={class:"container-xxl testimonial my-5 py-5 bg-dark wow zoomIn","data-wow-delay":"0.1s ",style:{"margin-bottom":"90px"}},o={class:"container"},d={class:"owl-carousel testimonial-carousel py-5"},r={class:"d-flex align-items-center"},l=(0,s._)("i",{class:"fas fa-user-circle fa-3x"},null,-1),p={class:"ps-3"},u={class:"fw-bold mb-1"},f={key:0,class:"alert alert-success mt-3",role:"alert"};function m(t,a,e,m,g,h){const b=(0,s.up)("Top");return(0,s.wg)(),(0,s.iD)(s.HY,null,[(0,s.Wm)(b),n,(0,s._)("div",c,[(0,s._)("div",o,[(0,s._)("div",d,[((0,s.wg)(!0),(0,s.iD)(s.HY,null,(0,s.Ko)(g.feed,(t=>((0,s.wg)(),(0,s.iD)("div",{key:g.name.id,class:"testimonial-item position-relative bg-white rounded overflow-hidden"},[(0,s._)("div",r,[l,(0,s._)("div",p,[(0,s._)("h6",u,(0,i.zw)(h.getName(t).name),1)])]),(0,s._)("p",null,(0,i.zw)(t.feedback),1)])))),128))]),g.feedbackSent?((0,s.wg)(),(0,s.iD)("div",f," Feedback sent successfully! ")):(0,s.kq)("",!0)])])],64)}var g=e(1738),h=e(1076),b={name:"testimonials",components:{Top:g.Z},data(){return{feed:[],name:[],feedbackSent:!1}},async mounted(){await this.getFeed()},methods:{async getFeed(){try{const[t,a]=await Promise.all([h.Z.get("/getFeedback"),h.Z.get("/getData")]);this.feed=t.data,this.name=a.data}catch(t){console.error("Error fetching data:",t)}},getName(t){return this.name.find((a=>a.id===t.id))||{}}}},v=e(89);const w=(0,v.Z)(b,[["render",m]]);var y=w},4131:function(t,a,e){e.r(a),e.d(a,{default:function(){return L}});var s=e(3396),i=e(7139),n=e(9242);const c=t=>((0,s.dD)("data-v-c8b3fcfa"),t=t(),(0,s.Cn)(),t),o=(0,s.uE)('<div class="container-xxl bg-white p-0" data-v-c8b3fcfa><div class="room" data-v-c8b3fcfa><div class="container-fluid page-header mb-5 p-0" data-v-c8b3fcfa><div class="container-fluid page-header-inner py-5" data-v-c8b3fcfa><div class="container text-center pb-5" data-v-c8b3fcfa><h1 class="display-3 text-white mb-3 animated slideInDown" data-v-c8b3fcfa> Shop </h1><nav aria-label="breadcrumb" data-v-c8b3fcfa><ol class="breadcrumb justify-content-center text-uppercase" data-v-c8b3fcfa><li class="breadcrumb-item" data-v-c8b3fcfa><a href="#" data-v-c8b3fcfa> Home </a></li><li class="breadcrumb-item" data-v-c8b3fcfa><a href="#" data-v-c8b3fcfa> Pages </a></li><li class="breadcrumb-item text-white active" aria-current="page" data-v-c8b3fcfa> Contact </li></ol></nav></div></div></div></div></div>',1),d={class:"container-xxl py-5"},r={class:"container"},l=c((()=>(0,s._)("div",{class:"text-center wow fadeInUp","data-wow-delay":"0.1s"},[(0,s._)("h6",{class:"section-title text-center text-primary text-uppercase"}," Our Shop "),(0,s._)("h1",{class:"mb-5"},[(0,s.Uk)(" Explore Our "),(0,s._)("span",{class:"text-primary text-uppercase"}," Shop ")])],-1))),p={class:"row g-4"},u={class:"col-lg-4 col-md-6 wow fadeInUp","data-wow-delay":"0.1s"},f={class:"room-item shadow rounded overflow-hidden card-menu"},m={class:"position-relative"},g=["src"],h={class:"position-absolute start-0 top-100 translate-middle-y bg-primary text-white rounded py-1 px-3 ms-4"},b={class:"p-4 mt-2"},v={class:"d-flex justify-content-between mb-3"},w={class:"mb-0 text-dark"},y={class:"ps-2"},_={class:"d-flex mb-3"},x={class:"border-end me-3 pe-3"},k={class:"text-body mb-3"},R={class:"d-flex justify-content-between"},Z={class:"input-group"},S=["onClick"],D=c((()=>(0,s._)("i",{class:"fa fa-shopping-cart"}," Add to Cart ",-1))),C=[D],E={key:0,class:"alert alert-primary mt-3",role:"alert"},N=(0,s.uE)('<div class="container newsletter mt-5 wow fadeIn" data-wow-delay="0.1s" data-v-c8b3fcfa><div class="row justify-content-center" data-v-c8b3fcfa><div class="col-lg-10 border rounded p-1" data-v-c8b3fcfa><div class="border rounded text-center p-1" data-v-c8b3fcfa><div class="bg-white rounded text-center p-5" data-v-c8b3fcfa><h4 class="mb-4" data-v-c8b3fcfa> Subscribe Our <span class="text-primary text-uppercase" data-v-c8b3fcfa> Newsletter </span></h4><div class="position-relative mx-auto" style="max-width:400px;" data-v-c8b3fcfa><input class="form-control w-100 py-3 ps-4 pe-5" type="text" placeholder="Enter your email" data-v-c8b3fcfa><button type="button" class="btn btn-primary py-2 px-3 position-absolute top-0 end-0 mt-2 me-2" data-v-c8b3fcfa> Submit </button></div></div></div></div></div></div>',1);function F(t,a,e,c,D,F){const T=(0,s.up)("Top"),z=(0,s.up)("spinner"),I=(0,s.up)("navbar"),W=(0,s.up)("star-rating"),q=(0,s.up)("feedbacks"),H=(0,s.up)("End"),P=(0,s.up)("Notification");return(0,s.wg)(),(0,s.iD)(s.HY,null,[(0,s.Wm)(T),(0,s.Wm)(z),(0,s.Wm)(I),o,(0,s._)("div",d,[(0,s._)("div",r,[l,(0,s._)("div",p,[((0,s.wg)(!0),(0,s.iD)(s.HY,null,(0,s.Ko)(D.shop,(t=>((0,s.wg)(),(0,s.iD)("div",u,[(0,s._)("div",f,[(0,s._)("div",m,[(0,s._)("img",{class:"custom-image",src:`https://eduardos-resort.online/backend/backend/public/uploads/${t.prod_img}`,alt:""},null,8,g),(0,s._)("small",h," Php. "+(0,i.zw)(t.prod_price),1)]),(0,s._)("div",b,[(0,s._)("div",v,[(0,s._)("h5",w,(0,i.zw)(t.prod_name),1),(0,s._)("div",y,[(0,s.Wm)(W,{initialRating:t.rating,onRatingSelected:a=>F.updateRating(t.shop_id,a)},null,8,["initialRating","onRatingSelected"])])]),(0,s._)("div",_,[(0,s._)("small",x," Quantity: "+(0,i.zw)(t.prod_quantity),1)]),(0,s._)("p",k,(0,i.zw)(t.prod_desc),1),(0,s._)("div",R,[(0,s._)("div",Z,[(0,s.wy)((0,s._)("input",{type:"number",class:"form-control text-center","onUpdate:modelValue":a[0]||(a[0]=t=>D.selectedQuantity=t),min:"1"},null,512),[[n.nr,D.selectedQuantity]])]),(0,s._)("button",{class:"btn btn-add",onClick:a=>F.addCart(t.shop_id)},C,8,S)])])])])))),256)),t.successMessage?((0,s.wg)(),(0,s.iD)("div",E,(0,i.zw)(t.successMessage),1)):(0,s.kq)("",!0)])])]),(0,s.Wm)(q),N,(0,s.Wm)(H),(0,s.Wm)(P,{show:D.notification.show,type:D.notification.type,message:D.notification.message},null,8,["show","type","message"])],64)}e(560);var T=e(1738),z=e(4085),I=e(4037),W=e(8693);const q={class:"text-primary"},H=["onClick"],P={class:"rating-info"};function Q(t,a,e,n,c,o){return(0,s.wg)(),(0,s.iD)("div",null,[(0,s._)("div",q,[((0,s.wg)(!0),(0,s.iD)(s.HY,null,(0,s.Ko)(c.stars,(t=>((0,s.wg)(),(0,s.iD)("i",{key:t,onClick:a=>o.selectRating(t),class:(0,i.C_)({" fas fa-star":t<=c.selectedRating,"far fa-star":t>c.selectedRating})},null,10,H)))),128))]),(0,s._)("div",P,[(0,s._)("p",null,"Current Rating: "+(0,i.zw)(c.selectedRating),1)])])}var U=e(1076),Y={components:{Top:T.Z},props:{initialRating:Number},data(){return{selectedRating:this.initialRating||0,stars:[1,2,3,4,5],rating:[]}},mounted(){this.getRating()},methods:{selectRating(t){this.selectedRating=t,this.$emit("rating-selected",t)},async getRating(){const t=await U.Z.get("/getShop");this.rating=t.data}}},j=e(89);const K=(0,j.Z)(Y,[["render",Q]]);var M=K,O=e(5429),$=e(9754),B={name:"Shop",components:{Notification:$.Z,spinner:O.Z,Top:T.Z,navbar:z.Z,End:W.Z,feedbacks:I.Z,StarRating:M},data(){return{feed:[],shop:[],name:[],quantity:1,selectedQuantity:1,notification:{show:!1,type:"",message:""}}},mounted(){this.getFeed(),this.getShop()},methods:{updateRating(t,a){this.submitRatingToBackend(t,a)},async submitRatingToBackend(t,a){try{const e=await U.Z.post("/submit-rating",{shop_id:t,rating:a});console.log(e.data)}catch(e){console.error(e)}},async getFeed(){const[t,a]=await Promise.all([U.Z.get("/getFeedback"),U.Z.get("/getData")]);this.feed=t.data,this.name=a.data},async getShop(){const t=await U.Z.get("/getShop");this.shop=t.data},async addCart(t){const a=sessionStorage.getItem("id");if(a)try{const e=this.shop.find((a=>a.shop_id===t)),s=this.selectedQuantity;if(e.prod_quantity>=s){await U.Z.post("getCart",{shop_id:t,id:a,quantity:s});this.showSuccessNotification("Product added to cart successfully"),setTimeout((()=>{this.successMessage=""}),2e3)}else this.showErrorNotification("Product is out of stock")}catch(e){console.error("Error adding to cart",e),this.showErrorNotification("Failed to add product to cart")}else this.$router.push({name:"LoginForm"})},showSuccessNotification(t){this.notification={show:!0,type:"success",message:t},setTimeout((()=>{this.notification.show=!1}),2e3)},showErrorNotification(t){this.notification={show:!0,type:"error",message:t},setTimeout((()=>{this.notification.show=!1}),2e3)}}};const A=(0,j.Z)(B,[["render",F],["__scopeId","data-v-c8b3fcfa"]]);var L=A}}]);
//# sourceMappingURL=131.26d87500.js.map