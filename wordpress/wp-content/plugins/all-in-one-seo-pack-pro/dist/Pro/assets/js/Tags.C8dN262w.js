import{d as l,h as t}from"./links.D18SrdNe.js";import{c as p}from"./postSlug.DgPEdjzX.js";const f={methods:{parseTags(e){const n=l();return!e||!n.tags?e:(n.tags.forEach(o=>{if(o.id==="custom_field"){const a=new RegExp(`#${o.id}-([a-zA-Z0-9_-]+)`),r=e.match(a);r&&r[1]&&(e=e.replace(a,p(r[1])));return}if(o.id==="tax_name"){const a=new RegExp(`#${o.id}-([a-zA-Z0-9_-]+)`,"g");e=e.replace(a,`[${o.name} - $1]`);return}const c=new RegExp(`#${o.id}(?![a-zA-Z0-9_])`,"g");o.id==="separator_sa"&&this.separator!==void 0&&(e=e.replace(c,this.separator));const s=e.match(c),u=n.liveTags[o.id]||o.value;s&&(e=e.replace(c,"%|%"+u))}),e=e.replace(/%\|%/g,""),t.decode(t.decode(e.replace(/<(?:.|\n)*?>/gm," ").replace(/\s/g," "))))}}};export{f as T};
