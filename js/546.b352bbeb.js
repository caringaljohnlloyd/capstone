"use strict";(self["webpackChunkeduardo_app"]=self["webpackChunkeduardo_app"]||[]).push([[546],{5546:function(t,e,o){o.r(e),o.d(e,{default:function(){return z}});var a=o(3396),r=o(7139);const n=t=>((0,a.dD)("data-v-25e68cfb"),t=t(),(0,a.Cn)(),t),i={class:"main-content"},s=n((()=>(0,a._)("h2",null,"Booking History",-1))),l={key:0},c=n((()=>(0,a._)("thead",null,[(0,a._)("tr",null,[(0,a._)("th",null,"Name"),(0,a._)("th",null,"Booking Date"),(0,a._)("th",null,"Details")])],-1))),g={key:1},h=n((()=>(0,a._)("h2",null,"Cottage History",-1))),y={key:2},u=n((()=>(0,a._)("thead",null,[(0,a._)("tr",null,[(0,a._)("th",null,"Name"),(0,a._)("th",null,"Cottage Date"),(0,a._)("th",null,"Details")])],-1))),d={key:3},_=n((()=>(0,a._)("h2",null,"Reservation History",-1))),w={key:4},H=n((()=>(0,a._)("thead",null,[(0,a._)("tr",null,[(0,a._)("th",null,"Name"),(0,a._)("th",null,"Reservation Date"),(0,a._)("th",null,"Details")])],-1))),f={key:5};function k(t,e,o,n,k,D){const m=(0,a.up)("Notification");return(0,a.wg)(),(0,a.iD)(a.HY,null,[(0,a.Wm)(m,{show:k.notification.show,type:k.notification.type,message:k.notification.message},null,8,["show","type","message"]),(0,a._)("div",i,[s,k.bookingHistory.length?((0,a.wg)(),(0,a.iD)("table",l,[c,(0,a._)("tbody",null,[((0,a.wg)(!0),(0,a.iD)(a.HY,null,(0,a.Ko)(k.bookingHistory,((t,e)=>((0,a.wg)(),(0,a.iD)("tr",{key:e},[(0,a._)("td",null,(0,r.zw)(D.getUserName(t.user_name)),1),(0,a._)("td",null,(0,r.zw)(t.date),1),(0,a._)("td",null,(0,r.zw)(t.details),1)])))),128))])])):((0,a.wg)(),(0,a.iD)("p",g,"Loading booking history...")),h,k.cottageHistory.length?((0,a.wg)(),(0,a.iD)("table",y,[u,(0,a._)("tbody",null,[((0,a.wg)(!0),(0,a.iD)(a.HY,null,(0,a.Ko)(k.cottageHistory,((t,e)=>((0,a.wg)(),(0,a.iD)("tr",{key:e},[(0,a._)("td",null,(0,r.zw)(D.getUserName(t.user_name)),1),(0,a._)("td",null,(0,r.zw)(t.date),1),(0,a._)("td",null,(0,r.zw)(t.details),1)])))),128))])])):((0,a.wg)(),(0,a.iD)("p",d,"Loading cottage history...")),_,k.reservationHistory.length?((0,a.wg)(),(0,a.iD)("table",w,[H,(0,a._)("tbody",null,[((0,a.wg)(!0),(0,a.iD)(a.HY,null,(0,a.Ko)(k.reservationHistory,((t,e)=>((0,a.wg)(),(0,a.iD)("tr",{key:e},[(0,a._)("td",null,(0,r.zw)(D.getUserName(t.user_name)),1),(0,a._)("td",null,(0,r.zw)(t.date),1),(0,a._)("td",null,(0,r.zw)(t.details),1)])))),128))])])):((0,a.wg)(),(0,a.iD)("p",f,"Loading reservation history..."))])],64)}var D=o(1076),m=o(9646);m.kL.register(...m.zX);var v={name:"analytics",data(){return{bookingHistory:[],cottageHistory:[],reservationHistory:[],user:{},notification:{show:!1,type:"",message:""}}},mounted(){this.fetchAllData()},methods:{async fetchAllData(){try{await Promise.all([this.fetchUsers(),this.fetchBookingHistory(),this.fetchCottageHistory(),this.fetchReservationHistory()])}catch(t){console.error("Error fetching data:",t)}},async fetchUsers(){try{const t=await D.Z.get("/getData");console.log("User Data:",t.data),t.data.forEach((t=>{this.user[t.id]=t.name}))}catch(t){console.error("Error fetching user data:",t)}},async fetchBookingHistory(){try{const t=await D.Z.get("/getRoomBookingHistory");console.log("Booking History Data:",t.data),this.bookingHistory=t.data}catch(t){console.error("Error fetching booking history:",t)}},async fetchCottageHistory(){try{const t=await D.Z.get("/getCottageHistory");console.log("Cottage History Data:",t.data),this.cottageHistory=t.data}catch(t){console.error("Error fetching cottage history:",t)}},async fetchReservationHistory(){try{const t=await D.Z.get("/getReservationHistory");console.log("Reservation History Data:",t.data),this.reservationHistory=t.data}catch(t){console.error("Error fetching reservation history:",t)}},getUserName(t){return this.user[Number(t)]||"Unknown User"}}},b=o(89);const p=(0,b.Z)(v,[["render",k],["__scopeId","data-v-25e68cfb"]]);var z=p}}]);
//# sourceMappingURL=546.b352bbeb.js.map