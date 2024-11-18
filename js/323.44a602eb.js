"use strict";(self["webpackChunkeduardo_app"]=self["webpackChunkeduardo_app"]||[]).push([[323],{323:function(a,l,s){s.r(l),s.d(l,{default:function(){return q}});var t=s(3396),n=s(7139);const e=a=>((0,t.dD)("data-v-2b6af53c"),a=a(),(0,t.Cn)(),a),o={class:"main-content"},r=["src"],i=e((()=>(0,t._)("i",{class:"fa-solid fa-chart-simple"},null,-1))),u=e((()=>(0,t._)("span",null,"Business Dashboard",-1))),d=e((()=>(0,t._)("summary",null,[(0,t._)("i",{class:"fa-solid fa-chart-line"}),(0,t.Uk)(" Inventory Options ")],-1))),c={class:"dropdown-menu"},_=e((()=>(0,t._)("i",{class:"fa-solid fa-chart-line"},null,-1))),f=e((()=>(0,t._)("span",null,"Room Inventory",-1))),p=e((()=>(0,t._)("i",{class:"fa-solid fa-chart-line"},null,-1))),m=e((()=>(0,t._)("span",null,"Shop Inventory",-1))),h=e((()=>(0,t._)("i",{class:"fa-solid fa-chart-line"},null,-1))),g=e((()=>(0,t._)("span",null,"Restaurant Reservations",-1))),b=e((()=>(0,t._)("i",{class:"fa-solid fa-chart-line"},null,-1))),v=e((()=>(0,t._)("span",null,"Enrollment Inventory",-1))),w=e((()=>(0,t._)("i",{class:"fa-solid fa-people-group"},null,-1))),y=e((()=>(0,t._)("span",null,"Team",-1))),D=e((()=>(0,t._)("i",{class:"fas fa-user"},null,-1))),S=e((()=>(0,t._)("span",null,"Users",-1))),k=e((()=>(0,t._)("i",{class:"fa-solid fa-table-columns"},null,-1))),C=e((()=>(0,t._)("span",null,"POS",-1))),U={class:"header"},I=e((()=>(0,t._)("h1",{class:"h1-main"},"EDUARDO'S ADMIN",-1))),W=e((()=>(0,t._)("i",{class:"fa-solid fa-bars",style:{padding:"5px",margin:"5px",width:"40px"}},null,-1))),E=[W],R=e((()=>(0,t._)("i",{class:"fas fa-power-off logout-icon"},null,-1))),x={class:"card-main"},z={class:"content"},Z={class:"row"},O={class:"col-12"},A={class:"card card-default"},L=e((()=>(0,t._)("div",{class:"card-header"},[(0,t._)("h2",{class:"fa-solid fa-users"}," Current Users"),(0,t._)("br"),(0,t._)("span",null,"Realtime")],-1))),M={class:"card-body"},N={class:"table-responsive"},B={class:"table table-product"},H=e((()=>(0,t._)("thead",null,[(0,t._)("tr",null,[(0,t._)("th",null,"User ID"),(0,t._)("th",null,"Username"),(0,t._)("th",null,"Email"),(0,t._)("th",null,"Status")])],-1)));function K(a,l,e,W,K,P){const T=(0,t.up)("router-link");return(0,t.wg)(),(0,t.iD)("div",o,[(0,t._)("div",{class:(0,n.C_)(["sidebar",{collapsed:K.isSidebarCollapsed}])},[(0,t._)("img",{src:s(2894),alt:"Mono",class:"logo"},null,8,r),(0,t.Wm)(T,{to:"/admin2"},{default:(0,t.w5)((()=>[i,u])),_:1}),(0,t._)("details",null,[d,(0,t._)("div",c,[(0,t.Wm)(T,{to:"/analytics2"},{default:(0,t.w5)((()=>[_,f])),_:1}),(0,t.Wm)(T,{to:"/shopinventory"},{default:(0,t.w5)((()=>[p,m])),_:1}),(0,t.Wm)(T,{to:"/reservations"},{default:(0,t.w5)((()=>[h,g])),_:1}),(0,t.Wm)(T,{to:"/enrollment"},{default:(0,t.w5)((()=>[b,v])),_:1})])]),(0,t.Wm)(T,{to:"/teamadmin2"},{default:(0,t.w5)((()=>[w,y])),_:1}),(0,t.Wm)(T,{to:"/monitorusers2"},{default:(0,t.w5)((()=>[D,S])),_:1}),(0,t.Wm)(T,{to:"/pos2"},{default:(0,t.w5)((()=>[k,C])),_:1})],2),(0,t._)("div",U,[I,(0,t._)("button",{class:"navbar-toggler",type:"button",onClick:l[0]||(l[0]=(...a)=>P.toggleSidebar&&P.toggleSidebar(...a))},E),(0,t._)("button",{onClick:l[1]||(l[1]=(...a)=>P.logout&&P.logout(...a)),class:"btn btn-custom logout-logo-btn"},[R,(0,t.Uk)(" Logout ")])]),(0,t._)("div",x,[(0,t._)("div",z,[(0,t._)("div",Z,[(0,t._)("div",O,[(0,t._)("div",A,[L,(0,t._)("div",M,[(0,t._)("div",N,[(0,t._)("table",B,[H,(0,t._)("tbody",null,[((0,t.wg)(!0),(0,t.iD)(t.HY,null,(0,t.Ko)(K.users,(a=>((0,t.wg)(),(0,t.iD)("tr",{key:a.id},[(0,t._)("td",null,(0,n.zw)(a.id),1),(0,t._)("td",null,(0,n.zw)(a.name),1),(0,t._)("td",null,(0,n.zw)(a.email),1),(0,t._)("td",null,(0,n.zw)(a.status),1)])))),128))])])])])])])])])])])}s(560);var P=s(1076),T=s(9754),Y={name:"inventory",components:{Notification:T.Z},data(){return{isSidebarCollapsed:!1,users:[]}},mounted(){this.fetchUserData()},created(){this.fetchUserData()},methods:{toggleSidebar(){this.isSidebarCollapsed=!this.isSidebarCollapsed},async logout(){try{const a=await P.Z.post("/logout");200===a.status&&(console.log("Logout successful"),sessionStorage.removeItem("token"),this.$router.push("/"))}catch(a){console.error("Error during logout",a)}},async fetchUserData(){try{const a=await P.Z.get("getData");this.users=a.data}catch(a){console.error("Error fetching user data:",a)}}}},$=s(89);const j=(0,$.Z)(Y,[["render",K],["__scopeId","data-v-2b6af53c"]]);var q=j},2894:function(a,l,s){a.exports=s.p+"img/logo1.png.63b3f6c0.png"}}]);
//# sourceMappingURL=323.44a602eb.js.map