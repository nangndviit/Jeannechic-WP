import{t as h}from"./toNumber.DjUzv07w.js";import{k as b}from"./default-i18n.Bd0Z306Z.js";import{m as k}from"./get.CYs9ONpq.js";import{a as E}from"./_arrayEach.Fgt6pfHj.js";import{w as R,f as x,x as y,a as _}from"./helpers.BOFCPzAH.js";import{k as v}from"./_getTag.B9PhEBdR.js";var d=1/0,w=17976931348623157e292;function Z(e){if(!e)return e===0?e:0;if(e=h(e),e===d||e===-d){var r=e<0?-1:1;return r*w}return e===e?e:0}function T(e,r){return e&&R(e,r,v)}function $(e,r){return function(n,s){if(n==null)return n;if(!x(n))return e(n,s);for(var t=n.length,c=r?t:-1,a=Object(n);(r?c--:++c<t)&&s(a[c],c,a)!==!1;);return n}}var j=$(T);const A=j;function F(e){return typeof e=="function"?e:y}function S(e,r){var n=_(e)?E:A;return n(e,F(r))}function z(e){return e=e.replace(/\s{2,}/g," "),e=e.replace(/\s\./g,"."),e.trim()}var g=function(e,r){var n;for(n=0;n<e.length;n++)if(e[n].regex.test(r))return e[n]},p=function(e,r){var n,s,t;for(n=0;n<r.length;n++)if(s=g(e,r.substring(0,n+1)),s)t=s;else if(t)return{max_index:n,rule:t};return t?{max_index:r.length,rule:t}:void 0},B=function(e){var r="",n=[],s=1,t=1,c=function(a,o){e({type:o,src:a,line:s,col:t});var i=a.split(`
`);s+=i.length-1,t=(i.length>1?1:t)+i[i.length-1].length};return{addRule:function(a,o){n.push({regex:a,type:o})},onText:function(a){for(var o=r+a,i=p(n,o);i&&i.max_index!==o.length;)c(o.substring(0,i.max_index),i.rule.type),o=o.substring(i.max_index),i=p(n,o);r=o},end:function(){if(r.length!==0){var a=g(n,r);if(!a){var o=new Error("unable to tokenize");throw o.tokenizer2={buffer:r,line:s,col:t},o}c(r,a.type)}}}};const I=b(B),f=["address","article","aside","blockquote","canvas","details","dialog","dd","div","dl","dt","fieldset","figcaption","figure","footer","form","h1","h2","h3","h4","h5","h6","header","hgroup","hr","li","main","nav","noscript","ol","output","p","pre","section","table","tfoot","ul","video"],m=["b","big","i","small","tt","abbr","acronym","cite","code","dfn","em","kbd","strong","samp","time","var","a","bdo","br","img","map","object","q","script","span","sub","sup","button","input","label","select","textarea"],N=new RegExp("^<("+f.join("|")+")[^>]*?>$","i"),L=new RegExp("^</("+f.join("|")+")[^>]*?>$","i"),M=new RegExp("^<("+m.join("|")+")[^>]*>$","i"),q=new RegExp("^</("+m.join("|")+")[^>]*>$","i"),C=/^<([^>\s/]+)[^>]*>$/,G=/^<\/([^>\s]+)[^>]*>$/,O=/^[^<]+$/,D=/^<[^><]*$/,P=/<!--(.|[\r\n])*?-->/g;let u=[],l;function U(){u=[],l=I(function(e){u.push(e)}),l.addRule(O,"content"),l.addRule(D,"greater-than-sign-content"),l.addRule(N,"block-start"),l.addRule(L,"block-end"),l.addRule(M,"inline-start"),l.addRule(q,"inline-end"),l.addRule(C,"other-element-start"),l.addRule(G,"other-element-end")}function X(e){const r=[];let n=0,s="",t="",c="";return e=e.replace(P,""),U(),l.onText(e),l.end(),S(u,function(a,o){const i=u[o+1];switch(a.type){case"content":case"greater-than-sign-content":case"inline-start":case"inline-end":case"other-tag":case"other-element-start":case"other-element-end":case"greater than sign":!i||n===0&&(i.type==="block-start"||i.type==="block-end")?(t+=a.src,r.push(t),s="",t="",c=""):t+=a.src;break;case"block-start":n!==0&&(t.trim()!==""&&r.push(t),t="",c=""),n++,s=a.src;break;case"block-end":n--,c=a.src,s!==""&&c!==""?r.push(s+t+c):t.trim()!==""&&r.push(t),s="",t="",c="";break}0>n&&(n=0)}),r}const ee=k(X),Y=new RegExp("</?("+f.join("|")+")[^>]*?>","ig"),ne=function(e){return e=e.replace(/<header[^>]*? class\s*=\s*["']?aioseo-toc-header[^>]+>([\S\s]*?)<\/header>/gms,""),e=e.replace(/<span[^>]*? class\s*=\s*["']?aioseo-tooltip[^>]+>([\S\s]*?)<\/span>/gms,""),e=e.replace(Y," "),e=e.replace(/(<([^>]+)>)/ig,""),e=z(e),e};export{z as a,A as b,I as c,S as f,ee as m,ne as s,Z as t};
