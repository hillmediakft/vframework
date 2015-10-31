/*
 Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.md or http://ckeditor.com/license
*/
CKEDITOR.addTemplates("default",
{
	imagesPath:CKEDITOR.getUrl(CKEDITOR.plugins.getPath("templates")+"my_templates/images/"),
	
	templates:
		[
			                      
        {
        title:"Szöveg dobozban",
	image:"szoveg_dobozban.jpg",
	description:"Szöveg dobozban",
	html: '<div class="well">Nullam tincidunt gravida erat, vel faucibus ligula luctus a.&nbsp;</div>'
        },
        
        {
        title:"Kiemelt szöveg",
	image:"szoveg_kiemeles.jpg",
	description:"Szöveg kiemelése nagyobb méretben",
	html: '<blockquote><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p></blockquote>'
        },        

{
        title:"Lista elem",
	image:"list.jpg",
	description:"Pipával ellátott lista",
	html: '<ul><li><i class="fa fa-check"></i>&nbsp;Lorem ipsus lures</li><li><i class="fa fa-check"></i>&nbsp;Lorem ipsus lures</li><li><i class="fa fa-check"></i>&nbsp;Lorem ipsus lures</li><li><i class="fa fa-check"></i>&nbsp;Lorem ipsus lures</li><li><i class="fa fa-check"></i>&nbsp;Lorem ipsus lures</li><li><i class="fa fa-check"></i>&nbsp;Lorem ipsus lures</li></ul>'
        },          

{
        title:"Gomb linkkel",
	image:"gomb_link.jpg",
	description:"Linket tartalmazó további részletek gomb",
	html: '<div class="margin-top-20 margin-bottom-20"><a href="#" class="btn btn-primary" target="_self">További részletek&nbsp;<i class="fa fa-arrow-right"></i></a></div>'
        },          
       
{
        title:"Link",
	image:"link_arrow.jpg",
	description:"Egyszerű link nyíllal",
	html: '<p class="margin-bottom-15 margin-top-20"><a href="#"><i class="fa fa-arrow-right"></i> További információ</a></p>'
        }         
		]
});