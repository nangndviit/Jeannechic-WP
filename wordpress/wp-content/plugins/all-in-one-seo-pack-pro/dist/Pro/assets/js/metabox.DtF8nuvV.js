import{l as d,u as x,a as f}from"./links.D18SrdNe.js";const _=(e=null)=>{var r,a,c,p,i,u,l;d();const o=x(),n=f();if(e)return!!((a=(r=o.dynamicOptions.searchAppearance.postTypes[e])==null?void 0:r.advanced)!=null&&a.showMetaBox);if(!((c=n.currentPost)!=null&&c.id))return!1;const t=n.currentPost.postType,s=n.currentPost.termType,m=!!(t&&n.currentPost.context==="post"&&o.dynamicOptions.searchAppearance.postTypes[t]&&((i=(p=o.dynamicOptions.searchAppearance.postTypes[t])==null?void 0:p.advanced)!=null&&i.showMetaBox)),y=!!(s&&n.currentPost.context==="term"&&o.dynamicOptions.searchAppearance.taxonomies[s]&&((l=(u=o.dynamicOptions.searchAppearance.taxonomies[s])==null?void 0:u.advanced)!=null&&l.showMetaBox));return m||y};export{_ as s};
