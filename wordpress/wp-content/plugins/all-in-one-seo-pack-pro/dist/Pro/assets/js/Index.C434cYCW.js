import{o as n,c,a as r,H as a}from"./vue.esm-bundler.DqIKZLqK.js";import{_ as i}from"./_plugin-vue_export-helper.BN1snXvA.js";const l={props:{scoreColor:String,score:{type:Number,required:!0},strokeWidth:{type:Number,default(){return 2}}},computed:{getClass(){let s="",e="";switch(!0){case 33>=this.score:s="fast",e="stroke-red";break;case 66>=this.score:s="medium",e="stroke-orange";break;default:s="slow",e="stroke-green"}return this.scoreColor!==void 0&&(e=`stroke-${this.scoreColor}`),`${s} ${e}`}}},u={class:"aioseo-seo-site-score-svg",viewBox:"0 0 34 34",xmlns:"http://www.w3.org/2000/svg"},_=["stroke-width","r"],d=["stroke-width","stroke-dasharray","r"];function f(s,e,o,h,k,t){return n(),c("svg",u,[r("circle",{class:"aioseo-seo-site-score__background","stroke-width":o.strokeWidth,fill:"none",cx:"17",cy:"17",r:17-o.strokeWidth/2},null,8,_),r("circle",{class:a(["aioseo-seo-site-score__circle",t.getClass]),"stroke-width":o.strokeWidth,"stroke-dasharray":`${o.score},100`,"stroke-linecap":"round",fill:"none",cx:"17",cy:"17",r:17-o.strokeWidth/2},null,10,d)])}const g=i(l,[["render",f]]);export{g as S};
