<?php $_TYPE='css'; include '../inc.php'; ?>

	body  { background:url(<?=IMG.'0101/bg.png'?>) repeat-x scroll 200px -100px #0D0D0D; }
	
	#main { background:url(<?=IMG.'layout.main.png'?>) repeat-y #FFF; }
	#mask { background:green; }
	#logo h1 { background:url(<?=IMG.'logo.white.png'?>) no-repeat center #00004A; }
	#logo h5 { color:#DDD; background:#333; border-bottom-color:#FFF; }
	
	#menu { background:#FFF; border-bottom-color:#333; }
		#menu li { background:url(<?=IMG.'layout.bullet.png'?>) no-repeat left 1.1em ; }
		#menu a 	  { color:#333; background:#FFF; }
		#menu a:hover { color:#000; border-bottom-color:#C90; }
	
	#info { border-bottom-color:#FFF; border-right-color:#FFF; background:#333; }
		#info h3 { color:#FFF; }
		#info h4 { color:#CCC;}
		#info p  { color:#999; }
		#info strong { color:#AAA; }
		#info p.sign { color:#FFF; }
		#info label span, #info textarea, #info input { color:#CCC; }
		#info textarea, #info label input { border-color:#666; border-color:#666; background:#404040; }
		#info .snd { background:#666; }
		#info .alt span { color:#F00; }
		#info .alt ul { color:#F99; }

	#cont { } 
	#cont .rgt { border-top-color:#FFF; }
		#navi { background:url(<?=IMG.'layout.navi.png'?>) no-repeat 3px center #808080; border-bottom-color:#333;}
		#navi a { background:#CCC; border-bottom-color:#FFF; }
		#navi a strong   { color:#333; }
		#navi a em 		 { color:#666; }
		#navi a:hover 	 { background:#C90; }
		#navi a:hover em { color:#FFF; }
		
		#mini { border-color:#FFF; }
		
		#look input.n1 { background:#EEE; color:#666; border-color:#808080; }
		#look input.n2 { background:url(<?=IMG.'layout.look.png'?>) no-repeat center #808080; }
		
		.ad { border-top-color:#FFF; }
		.ad span { background:url(<?=IMG.'layout.ad.gif'?>); }
	
	#covr .tit,
	#covr .n1 .covr { background:url(<?=IMG.'0101/covr.jpg'?>); }
	#cult .tit,
	#covr .n1 .cult { background:url(<?=IMG.'0101/covr.cult.jpg'?>); }
	#ecol .tit,
	#covr .n1 .ecol { background:url(<?=IMG.'0101/covr.ecol.jpg'?>); }
	#viaj .tit,
	#covr .n1 .viaj { background:url(<?=IMG.'0101/covr.viaj.jpg'?>); }
	#rest .tit,
	#covr .n1 .rest { background:url(<?=IMG.'0101/covr.rest.jpg'?>); }
	#luga .tit,
	#covr .n1 .luga { background:url(<?=IMG.'0101/covr.luga.jpg'?>); }
	#even .tit,
	#covr .n1 .even { background:url(<?=IMG.'0101/covr.even.jpg'?>); }
	#soci .tit,
	#covr .n1 .soci { background:url(<?=IMG.'0101/covr.soci.jpg'?>); }
	
	#covr .n2 .covr { background:url(<?=IMG.'0101/covr.nor.jpg'?>); }
	#covr .n2 .cult { background:url(<?=IMG.'0101/covr.cult.nor.jpg'?>); }
	#covr .n2 .ecol { background:url(<?=IMG.'0101/covr.ecol.nor.jpg'?>); }
	#covr .n2 .viaj { background:url(<?=IMG.'0101/covr.viaj.nor.jpg'?>); }
	#covr .n2 .rest { background:url(<?=IMG.'0101/covr.rest.nor.jpg'?>); }
	#covr .n2 .luga { background:url(<?=IMG.'0101/covr.luga.nor.jpg'?>); }
	#covr .n2 .even { background:url(<?=IMG.'0101/covr.even.nor.jpg'?>); }
	#covr .n2 .soci { background:url(<?=IMG.'0101/covr.soci.nor.jpg'?>); }
	#covr .n3 .covr { background:url(<?=IMG.'0101/covr.sml.jpg'?>); }
	#covr .n3 .cult { background:url(<?=IMG.'0101/covr.cult.sml.jpg'?>); }
	#covr .n3 .ecol { background:url(<?=IMG.'0101/covr.ecol.sml.jpg'?>); }
	#covr .n3 .viaj { background:url(<?=IMG.'0101/covr.viaj.sml.jpg'?>); }
	#covr .n3 .rest { background:url(<?=IMG.'0101/covr.rest.sml.jpg'?>); }
	#covr .n3 .luga { background:url(<?=IMG.'0101/covr.luga.sml.jpg'?>); }
	#covr .n3 .even { background:url(<?=IMG.'0101/covr.even.sml.jpg'?>); }
	#covr .n3 .soci { background:url(<?=IMG.'0101/covr.soci.sml.jpg'?>); }
	#covr .n4 .covr { background:url(<?=IMG.'0101/covr.lon.jpg'?>); }
	#covr .n4 .cult { background:url(<?=IMG.'0101/covr.cult.lon.jpg'?>); }
	#covr .n4 .ecol { background:url(<?=IMG.'0101/covr.ecol.lon.jpg'?>); }
	#covr .n4 .viaj { background:url(<?=IMG.'0101/covr.viaj.lon.jpg'?>); }
	#covr .n4 .rest { background:url(<?=IMG.'0101/covr.rest.lon.jpg'?>); }
	#covr .n4 .luga { background:url(<?=IMG.'0101/covr.luga.lon.jpg'?>); }
	#covr .n4 .even { background:url(<?=IMG.'0101/covr.even.lon.jpg'?>); }
	#covr .n4 .soci { background:url(<?=IMG.'0101/covr.soci.lon.jpg'?>); }
	#covr .n5 .covr { background:url(<?=IMG.'0101/covr.sml.jpg'?>); }
	#covr .n5 .cult { background:url(<?=IMG.'0101/covr.cult.sml.jpg'?>); }
	#covr .n5 .ecol { background:url(<?=IMG.'0101/covr.ecol.sml.jpg'?>); }
	#covr .n5 .viaj { background:url(<?=IMG.'0101/covr.viaj.sml.jpg'?>); }
	#covr .n5 .rest { background:url(<?=IMG.'0101/covr.rest.sml.jpg'?>); }
	#covr .n5 .luga { background:url(<?=IMG.'0101/covr.luga.sml.jpg'?>); }
	#covr .n5 .even { background:url(<?=IMG.'0101/covr.even.sml.jpg'?>); }
	#covr .n5 .soci { background:url(<?=IMG.'0101/covr.soci.sml.jpg'?>); }
	#covr .n6 .covr { background:url(<?=IMG.'0101/covr.nor.jpg'?>); }
	#covr .n6 .cult { background:url(<?=IMG.'0101/covr.cult.nor.jpg'?>); }
	#covr .n6 .ecol { background:url(<?=IMG.'0101/covr.ecol.nor.jpg'?>); }
	#covr .n6 .viaj { background:url(<?=IMG.'0101/covr.viaj.nor.jpg'?>); }
	#covr .n6 .rest { background:url(<?=IMG.'0101/covr.rest.nor.jpg'?>); }
	#covr .n6 .luga { background:url(<?=IMG.'0101/covr.luga.nor.jpg'?>); }
	#covr .n6 .even { background:url(<?=IMG.'0101/covr.even.nor.jpg'?>); }
	#covr .n6 .soci { background:url(<?=IMG.'0101/covr.soci.nor.jpg'?>); }
	#covr .n7 .covr { background:url(<?=IMG.'0101/covr.med.jpg'?>); }
	#covr .n7 .cult { background:url(<?=IMG.'0101/covr.cult.med.jpg'?>); }
	#covr .n7 .ecol { background:url(<?=IMG.'0101/covr.ecol.med.jpg'?>); }
	#covr .n7 .viaj { background:url(<?=IMG.'0101/covr.viaj.med.jpg'?>); }
	#covr .n7 .rest { background:url(<?=IMG.'0101/covr.rest.med.jpg'?>); }
	#covr .n7 .luga { background:url(<?=IMG.'0101/covr.luga.med.jpg'?>); }
	#covr .n7 .even { background:url(<?=IMG.'0101/covr.even.med.jpg'?>); }
	#covr .n7 .soci { background:url(<?=IMG.'0101/covr.soci.med.jpg'?>); }
	#covr .n8 .covr { background:url(<?=IMG.'0101/covr.med.jpg'?>); }
	#covr .n8 .cult { background:url(<?=IMG.'0101/covr.cult.med.jpg'?>); }
	#covr .n8 .ecol { background:url(<?=IMG.'0101/covr.ecol.med.jpg'?>); }
	#covr .n8 .viaj { background:url(<?=IMG.'0101/covr.viaj.med.jpg'?>); }
	#covr .n8 .rest { background:url(<?=IMG.'0101/covr.rest.med.jpg'?>); }
	#covr .n8 .luga { background:url(<?=IMG.'0101/covr.luga.med.jpg'?>); }
	#covr .n8 .even { background:url(<?=IMG.'0101/covr.even.med.jpg'?>); }
	#covr .n8 .soci { background:url(<?=IMG.'0101/covr.soci.med.jpg'?>); }
	
		
	#foot { background:#333; border-top-color:#808080; color:#FFF; }
