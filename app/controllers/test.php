<?php if(!defined('OK')) die('<h1>403</h1>');

class test {
	private $cont = null;
	private $sect = null;
	
	function __construct(){
		// sections available
		$this->sect = array('covr','cult','ecol','viaj','rest','luga','even','soci');
		// content
		$this->cont = array(
			'covr' => array(
				'lnk' => '/0101/portada/oaxaca-patrimonio-cultural-de-la-humanidad',
				'tit' => 'Oaxaca <br/><small><em>Patrimonio Cultural de la Humanidad</em></small>',
				'ttt' => 'Oaxaca Patrimonio Cultural de la Humanidad',
				'cnt' => '
					<img class="flr" src="'.IMG.'0101/covr.01.jpg" title="Monasterio de Oaxaca"/>
					<p>El estado de Oaxaca es una mágica combinación de arquitectura colonial en cantera verde, zonas prehispánicas y una cultura indígena que se manifiesta en hermosas y profundas tradiciones, artesanías de primer nivel mundial y una gastronomía capaz de conquistar al más exigente de los comensales.</p>
					<p>Los servicios turísticos que la ciudad capital de Oaxaca provee, compiten con los mejores de cualquier otro destino internacional. Oaxaca ofrece excelentes hoteles, restaurantes, transportación, agencias de viaje y muchos otros servicios, que por primera vez le ofrecemos a través de internet, para que usted planee desde su hogar u oficina y disfrute su experiencia en Oaxaca y sus Valles Centrales.</p>
					<p>Poseen zonas arqueológicas, conventos del siglo XVI, templos barrocos, edificios civiles de la época colonial y museos; así también un importante acervo de manifestaciones culturales, en especial sus fiestas religiosas con sus típicas calendas y mayordomías.</p>
					<p>La segunda zona turística en importancia en el estado es la Costa; famosa por las playas de Puerto Escondido, Puerto Angel y las Bahías de Huatulco. </p>
					<p class="fll fx0">Oaxaca es una de las ciudades coloniales más hermosas del país, y <strong>patrimonio cultural de la humanidad</strong>, junto con el resto de los Valles Centrales, son el centro turístico más importante en el estado.</p>
					<p>En esta entidad, que destaca particularmente por su grandeza histórica y cultural, y que le ofrece la oportunidad de recorrer ciudades y pueblos habitados por aproximadamente 16 etnias indígenas que aún conservan sus antiguas costumbres, habitados por aproximadamente 16 etnias indígenas que aún conservan sus antiguas costumbres, podrá visitar la Ciudad de Oaxaca, capital del Estado, una de las Ciudades Coloniales con mejor traza urbana y mayor riqueza arquitectónica. </p>
					<p>Ahí podrá admirar sus edificios de estilo barroco, construidos en cantera verde, así como visitar sus museos más importantes, como el Museo de las Culturas de Oaxaca, alojado en el Ex Convento de Santo Domingo, y disfrutar de sus fiestas más tradicionales como la “Noche de Rábanos” y la “Guelaguetza”.</p>
					<img class="ful" src="'.IMG.'0101/covr.02.jpg" title="Monasterio de Oaxaca"/>
					<p>Cerca de ahí podrá admirar otros atractivos naturales como el Árbol del Tule, un sabino de aproximadamente 42 m de diámetro, el cual rebasa los 2,000 años de edad; las Lagunas de Chacahua, zona ecológica integrada por una amplia y exuberante zona de manglares y playas, así como las hermosas Bahías de Huatulco y Puerto Escondido, donde podrá descansar o practicar diversos deportes acuáticos.</p>
					<p>En Oaxaca también podrá visitar diversos sitios arqueológicos, entre los que destacan, la Ciudad Zapoteca de Monte Alban, declarada como Patrimonio de la Humanidad por la UNESCO, y Mitla, cuyo mayor atractivo es la ornamentación de grecas que presentan sus edificios.</p>				
					<p>Durante su visita a este estado no deje de saborear su exquisita gastronomía, así como adquirir los famosos “alebrijes” y artesanías de barro negro como recuerdo de su viaje.</p>'
			),
			'cult' => array(
				'lnk' => '/0101/cultura/oaxaca-sus-espacios-para-el-arte-y-sus-artistas',
				'tit' => 'Oaxaca, sus espacios para el arte y sus artistas <br/><small><em> por Carlos-Blas Galindo</em></small>',
				'ttt' => 'Oaxaca, sus espacios para el arte y su artistas por Carlos-Blas Galindo',
				'cnt' => '
					<div class="fx0">
						<h3>por Carlos Blas Galindo</h3>
						<p>Académico, crítico de arte, curador y artista visual. Desde 1999 radica en la ciudad de Oaxaca. Es licenciado en artes visuales por la Universidad Nacional Autónoma de México (UNAM) y cuenta con una maestría en Artes Visuales. Ha sido profesor a nivel posgrado en la Escuela Nacional de Artes Plásticas de la UNAM y en la Escuela de Diseño del Instituto Nacional de Bellas Artes (INBA), y a nivel licenciatura en la Escuela Nacional de Pintura, Escultura y Grabado de dicho Instituto. Labora como investigador en el Centro Nacional de Investigación, Documentación e Información de Artes Plásticas del INBA.</p>
						<p>Realiza electrográficas, performances e instalaciones. Ha presentado seis exposiciones individuales y ha expuesto de manera colectiva dentro y fuera de nuestro país. En las colecciones del Museo Nacional de la Estampa, del Museo de Arte Moderno (ambos en el DF), del Museo Internacional de Electrografía (Cuenca, España) y del Instituto de Artes Gráficas de Oaxaca se cuenta con obra suya. </p>
					</div>
					<p>Sin lugar a dudas, la ciudad de Oaxaca es uno de los lugares más propicios que existen en México para la creación artística y para el disfrute de las artes plásticas. El que esto sea así se debe a múltiples causas, pero una fundamental ha sido el prestigio que Rufino Tamayo (1899-1991) le dio a la profesión de artista plástico dentro y fuera de México, así como su actitud filantrópica una vez que logró fama y fortuna. En los años 70 del siglo pasado dio su anuencia para que el Taller de Artes Plásticas que se fundó en esa ciudad con el apoyo del gobierno de Oaxaca y del Instituto Nacional de Bellas Artes (INBA) llevara su nombre, centro educativo que a partir de entonces visitó con frecuencia para estar en contacto con sus estudiantes, quienes se inscribieron a ese taller atraídos, precisamente, por la reputación que Tamayo le había dado a la profesión de artista. También en aquella época donó el acervo que se exhibe en el Museo de Arte Prehispánico que asimismo lleva su nombre (Morelos 503).</p>
					<p>Su ejemplo fue seguido por Francisco Toledo (1940), quien al igual que Tamayo, luego de haber ganado prestigio y dinero tanto en México como en el extranjero, desde finales de la década de los 80 de la pasada centuria ha tenido la iniciativa de fundar instituciones y lograr para ellas aportes financieros del gobierno oaxaqueño, el INBA y de otras fuentes, los cuales complementa con recursos personales. Gracias a la tenacidad de Toledo y a sus constantes negociaciones es que hoy existen en la ciudad de Oaxaca, entre otros recintos, el Instituto de Artes Gráficas (IAGO) –que cuenta con la mejor biblioteca especializada en artes plásticas de todo el país– y el Museo de Arte Contemporáneo (MACO), ambos situados en el andador turístico Macedonio Alcalá. Y ha sido gracias al ambiente favorable que estos dos trascendentales artistas han propiciado, que en la ciudad de Oaxaca existen tan numerosos espacios dedicados a las artes plásticas y que tantos artistas producen ahí sus obras.</p>
					<img class="flc" src="'.IMG.'0101/cult.02.jpg" title="Pintura Rupestre"/>
					<p>Oaxaca es generosa con sus artistas. Con los provenientes de otras regiones de la entidad, de otras zonas del país (recuérdese que Toledo mismo no nació en el estado de Oaxaca) o de otras naciones, tanto como con los oriundos de esa ciudad. E incluso con aquellos que radican por ahora en otras ciudades, de modo semejante como acogió a Tamayo y a Toledo luego de que vivieron fuera de ahí. Merced a la intensa actividad artística que hay en Oaxaca, es que cuenta con galerías de arte de gran solidez. La decana es, sin duda, la Galería Quetzalli (Constitución 104), que luego de algún tiempo inaugurara, fuera de su sede principal, Bodega Quetzalli (Murguía 400). Quetzalli representa, entre otros artistas, a Francisco Toledo, Guillermo Olguín, Laurie Litowitz, Raúl Herrera y Sergio Hernández Y entre las de reciente aparición destaca la Galería Manuel García Arte Contemporáneo, en pleno zócalo de la ciudad (Portal Benito Juárez 110, altos), dedicada a los autores que practican lenguajes de avanzada, como los fotógrafos Adriana Calatayud y Alejandro Echeverría, los artistas post-conceptuales Joel Gómez y Luis Hampshire o la videoasta Jessica Wozny.</p>
					<p>Hay quienes atribuyen parte del éxito de la ciudad de Oaxaca como uno de los lugares más propicios de México para la creación artística y para el disfrute de las artes plásticas, a su peculiar luminosidad. Es posible. Sin embargo tal éxito se debe, básicamente, a la tenacidad de autores que continúan y modifican las tradiciones que han heredado. Tal es el caso de Demián Flores Cortés (1971), quien ha emulado a Tamayo y a Toledo en cuanto a prestigio profesional dentro y fuera del país, y en cuanto a destinar parte de los ingresos que obtiene por la venta de sus obras a impulsar el desarrollo de la infraestructura cultural de la ciudad, tal y como lo hace subsidiando La Curtiduría, Espacio Contemporáneo para las Artes (5 de Mayo 307, Barrio de Jalatlaco) y apoyando el Taller de Gráfica Actual.</p>'
			),
			'ecol' => array(
				'lnk' => '/0101/ecologia/atributos-naturales-de-oaxaca',
				'tit' => 'Atributos naturales de Oaxaca <br/><small><em> por Biól. Patricia Santos</em></small>',
				'ttt' => 'Atributos naturales de Oaxaca por Biól. Patricia Santos',
				'cnt' => '
						<p>México es un país lleno de vidas distintas, así en plural, la biodiversidad es una característica nacional. En tierras y mares tenemos una abundancia de especies que nos coloca entre los cinco países con mayor riqueza vital a nivel mundial.</p>
						<p class="fx0">La UNESCO a través de la Red Mundial de Reserva de la Biósfera del Programa sobre el Hombre y la Biósfera (MAB Men an Biosphere) reconoce en el estado el área natural de Huatulco, y el gobierno de México además la de Tehuacán-Cuicatlán que se comparte con el estado de Puebla.</p>
						<p>Diversas circunstancias históricas y biogeográficas, sumadas a la diversidad de formas que originan nuestras cadenas montañosas, hacen posible esta riqueza y belleza.</p>
						<p>En Oaxaca coexisten valles protegidos por importantes cadenas montañosas, magníficos escenarios naturales como las espectaculares cascadas pétreas de Hierve el Agua, brumosas montañas, amplias planicies. Por su compleja geografía, Oaxaca es uno de los estados que más climas presenta, desde el frío de la montaña hasta la cálida costa.</p>
						<p>Esta situación propicia una importante diversidad en formas de vida, por ejemplo tan sólo en el territorio chinanteco encontramos unas 200 especies de reptiles, 2,204 de plantas vasculares, 530 de aves, 212 de mamíferos y 93 de anfibios.</p>
						<p>En esta ocasión nos referiremos a estas dos zonas como atributos naturales de Oaxaca, por su alto significado biológico:</p>
						<h3>La zona semi-árida de Cuicatlán</h3>
						<img class="fll" src="'.IMG.'0101/ecol.01.jpg" title="Zona semi-árida de Cuicatlán Oaxaca"/>
						<p>Nuestro país tiene más de 50 millones de años de equilibrio ecológico, por esa razón existen en nuestra geografía zonas semidesérticas como la de Tehua-cán-Cuicatlán entre Puebla y Oaxaca y como el del Río Tehuantepec en Oaxaca. Estos sitios han sido estables durante todos estos años, permitiendo su gran variedad de cactáceas, familia florística en la que México ocupa el primer lugar a nivel mundial.</p>
						<p>Esta zona se caracteriza por su vegetación de selva baja caducifolia , es decir, plantas que pierden sus hojas en la temporada más seca, así como de matorral xerófilo, que en algunas partes se mezcla con encinos y pinos.</p>
						<p>Las características biológicas de esta zona han favorecido la aparición de variados microclimas , y una gran cantidad de plantas únicas en la región: 81 tipos de cactáceas y más de 800 variedades de plantas vasculares representadas principalmente por tetechos y cardones. Destacan también los sotolines, los garambullos, biznagas, ocotillos, agaves y magueyes. Muchas de estas especies vegetales son susceptibles de uso medicinal, industrial, forrajero u ornamental.</p>
						<p>Estas bellas zonas son el amplio reinado de tonalidades encendidas interrumpidas por el majestuoso vertical de los enormes cactus. Los atardeceres invernales estallan en llamaradas cuando estas enormes plantas son alcanzados por la última luz del día. Entre los tonos dorados por la maleza marchita y deshidratada, destaca la colosal figura de los cactos cilíndricos como el órgano, el teteche, y el zarahuaro, o por los que ostentan figuras de candelabro como el “peine de los índios” el cardón y el garambullo. Como telón de fondo a este escenario, el régimen de lluvias determina que el cielo esté siempre abierto y su azul sea prístino. El clima extremoso en el ciclo día-noche, propicia la construcción de refugios, guaridas, oquedades, y otras formas de resguardo que diversifican la arquitectura del espacio.</p>
						<img class="ful" src="'.IMG.'0101/ecol.02.jpg" title="Zona semi-árida de Cuicatlán Oaxaca"/>
						<p>Desde estos altos cactos las aves depredadoras y rapaces acechan a sus víctimas y durante la época de floración y fructificación ofrecen un delicioso atractivo para los animales que se alimentan de los néctares, pulpas y semillas. En el día miles de insectos y numerosas especies de aves acuden al encuentro de su alimento, y por la noche son el sitio de encuentro de las aladas criaturas nocturnas.</p>
						<p>Las amplias planicies ofrecen un espacio franco y abierto que permiten la observación de fauna mayor, como la errática ruta del coyote y las rutas rectas de las zorras y los correcaminos. </p>
						<p>Las zonas semiáridas permanecen tranquilas y apacibles durante largas temporadas, presenciando sólo el paso del polvo, pero cuando llueve las plantas se precipitan a gozar de su efímera vida y en pocos días las semillas guardadas por largos períodos germinan, crecen y florecen. Ésta fugaz bonanza repercute en toda la vida del lugar, millones de individuos se lanzan sobre flores y frutos o forrajean intensivamente. Durante esta abundancia roedores y muchos otros animales surten su madriguera.</p>
						<p>Las especies de animales endémicos son más de medio centenar, y 16 se clasifican como raros, sin embargo, unas treinta especies se encuentran amenazadas o en peligro de extinción. Distinguimos entre los mamíferos: venado cola blanca, puma, jaguarundi, y pecarí de collar, entre las aves: guacamaya verde, águila real, pájaros carpinteros y correcaminos, y entre los reptiles: víbora de cascabel, coralillo, bejuquillo y una de las dos especies de lagartijas venenosas en el mundo llamada escorpión. Se tienen identificadas una gran cantidad de invertebrados que forman parte de la cadena alimentaria natural.</p>
						<h3>Bahías de Huatulco</h3>
						<img class="flr" src="'.IMG.'0101/ecol.03.jpg" title="Bahias de Huatulco"/>
						<p>En lo alto de la Sierra Madre del Sur, encontramos cultivos de café, enclavados entre montañas, ceibas y orquídeas con brisa de cascadas y melodías de aves exóticas.... al descender por las estribaciones de esta sierra, hacia el Océano Pacífico, se encuentra el escenario de las Bahías de Huatulco, que es casi un capricho geográfico. Las montañas se deslizan e ingresan suavemente bajo las aguas del mar formando un sistema de nueve bahías que dan lugar a 36 playas pobladas de vegetación, mientras olas de diferentes intensidades bañan las playas que son sólo entradas del océano bajo cuya superficie encontramos maravillosas formaciones de coral.</p>
						<p>Dos diferentes ecosistemas coexisten en esta zona. En la porción terrestre, la selva baja, donde predominan los cuachalates, palos de mulato, de arco y de iguana, pochotes y ciruelos, y en menor cantidad mezquites, guanacastles, tololotes y una pequeña porción de plantas de humedal y manglares. En este contexto encontramos 95 especies de vertebrados de las que 56 están sujetas a algún régimen de protección especial. Hay 278 especies de aves, de ésas casi 100 sólo llegan a esta parte de Oaxaca a invernar en los hábitat de vegetación acuática. Entre los mamíferos identificados se cuentan murciélagos, ardillas, tlacuaches, tejones, tuzas, zorrillos, puercoespines, zorras grises, coyotes, ocelotes, jabalíes y venados cola blanca.</p>
						<img class="fll" src="'.IMG.'0101/ecol.04.jpg" title="Bahias de Huatulco"/>
						<p>La porción rocosa y marina, se encuentra poblada por más de 60 especies de algas y macroalgas que flotan placidamente en el mar o cubren las rocas. Entre la fauna se cuenta con 12 tipos diferentes de coral, destacando las colonias de Violín y Chachacual, únicas en el Pacífico Mexicano. </p>
						<p>Entre la fauna de mayor talla se cuentan barriletes, cazones, huachinangos, bonitos, jureles, pargos, marlines y peces vela. Los invertebrados están representados principalmente por ostras de roca, lapas, pulpos y caracoles. </p>
						<p>Esta zona tiene el privilegio de contar con un invertebrado endémico: el caracol púrpura, que se encuentra bajo un estatus especial de protección por su importancia ecológica y económica en la obtención de tinta por parte de los mixtecos.</p>
						<p>Es importante mencionar, que muchas zonas de estos ecosistemas no se han estudiado completamente, ni se conoce en su totalidad el monto de su riqueza natural, por lo que tenemos el compromiso y responsabilidad de conservarlos.</p>'
			),
			'viaj' => array(
				'lnk' => '/0101/viajes/cienfuegos-la-perla-del-sur',
				'tit' => 'Cienfuegos, la perla del Sur',
				'ttt' => 'Cienfuegos, la perla del Sur',
				'cnt' => '
					<div class="fx0">
						<h3>LUGARES DE INTERÉS</h3>
						<ul>
							<li>El Jardín Botánico de Cienfuegos declarado Monumento Nacional el 20 de octubre de 1989.</li>
							<li>“El Cementerio de Reina” ejemplo excepcional de su tipo.</li>
							<li>La “Fortaleza de Nuestra Señora de los Ángeles de Jagua”, situado en la entrada al Puerto de Cienfuegos, este castillo fue construido en el siglo XVIII (1792) para defender la ciudad de los asaltos de piratas y filibusteros.</li>
							<li>El “Cementerio Tomás Acea”.</li>
							<li>El parque “José Martí”.</li>
							<li>El “Teatro Tomás Terry”</li>
						</ul>
					</div>
					<p>La ciudad de Cienfuegos, conocida como La Perla del Sur, es uno de los más importantes puertos de la costa sur de Cuba y capital de la provincia del mismo nombre (anteriormente provincia de Las Villas).</p>
					<p>Debe su origen al interés de las autoridades coloniales españolas por desarrollar nuevas ciudades en la isla. Fue fundada el 22 de abril de 1819 por colonos franceses y españoles en una excepcional bahía que sirvió de refugio a los piratas y corsarios. Las calles fueron trazadas formando cuadrículas. La arquitectura de la ciudad conserva abundante decoración neoclásica. Indiscutiblemente es una de las ciudades de mejor trazado que existen en Cuba, de calles anchas y rectas, de bellos paseos y parques, de edificios modernos y antiguos que responden totalmente al conjunto exterior que la rodea.</p>
					<p>En julio de 2005 el centro histórico fue declarado Patrimonio de la Humanidad por la UNESCO.</p>
					<p>Está ubicada en la llamada península Demajagua, a la orilla de la bahía de Jagua, de 88 km², al fondo de la misma. Esta bahía de nombre aborigen está abierta al Mar Caribe por un estrecho canal que sirve de acceso a las embarcaciones que usan el puerto de Cienfuegos. Se localiza a 256 km de la Cd. de La Habana y a 658 de Santiago de Cuba la segunda ciudad más importante de Cuba.</p>
					<p>En torno a Cienfuegos encuentra el visitante escenarios apropiados para la práctica del buceo, los deportes náuticos, el turismo de naturaleza y los baños termales.</p>
					<div class="l1">
						<h3>PERSONAJES NACIDOS EN CIENFUEGOS</h3>
						<ul>
							<li>Benny Moré (Músico).</li>
							<li>Orquesta Aragón (Grupo de Cha-Cha- Chá)</li>
							<li>Los Naranjos (Grupo musical histórico de Cuba)</li>
							<li>Mateo Torriente (Dibujante)</li>
							<li>Mercedes Matamoros (Maestra y poetisa, conocida como la “Inolvidable cantora del dolor”)</li>
							<li>Luisa Martínez Casado (Las más destacada artista teatral de Cienfuegos).</li>
						</ul>
					</div>'
			),
			'rest' => array(
				'lnk' => '/0101/restaurantes/sugoi-fusion-franco-japonesa',
				'tit' => 'Sugoi <br/><small><em>Fusión Franco-Japonesa</em></small>',
				'ttt' => 'Sugoi Fusión Franco-Japonesa',
				'cnt' => '
					<p>Tenemos buenas noticias, es un feliz hallazgo el descubrimiento del Restaurante SUGOI, ahora en su fase de cocina de fusión Franco-Japonesa, estilos, condimentos, salsas, carnes, que no parecerían pudieran armonizarse; Son ahora un gran menú, esta cocina de autor es la creación del chef Cristian L. Morales, donde encontramos los sabores y las cocciones, en parrillas, al vapor y fritas de la comida Japonesa, inclinada fuertemente en el arroz, mariscos, pescados, espinacas, pepinos, berenjenas, cebolla verde, tallarines, con la cocina Francesa , donde la mantequilla, créme, aceite de oliva, setas, finas hierbas, pescados y patatas son los ingredientes dominantes. </p>
					<img class="flr" src="'.IMG.'0101/rest.02.jpg" title="SUGOI, fusión franco-japonesa"/>
					<p>Su menú lo componen platos delicados, por la suavidad de sus aromas y sabores, para la vista, la presentación de sus platos son una obra de arte, y su servicio es atento y ceremonioso, como corresponde a la cultura Japonesa. Tema casi insólito por que los chefs de los restaurantes en Cancún algunos de ellos suelen personalizar su atención en los clientes frecuentes o amigos personales; aquí no siendo uno cliente habitual del Restaurante recibimos la atención personal del Chef Cristian L. Morales en nuestra mesa. Es aquí donde él con su gran experiencia nos conduce a la elección perfecta de los platos ya elegidos, sin desaprovechar la magnifica cava, también nos ofrece una cata de los vinos que el considera, serán los indicados para asegurarse que; el maridaje también sea compartido por el gusto personal del cliente. </p>
					<p>Al término de la comida las combinaciones de té que nos sugiere son sensacionales, cito el ejemplo del Té de Jazmín, su sabor y olor son exquisitos, la preparación del Té en la mesa es casi un rito, salvando la diferencia de que nuestros anfitriones no son Japoneses. </p>
					<p>Todos estos elementos nos garantizan el placer de una gran comida.</p>
					<p>El comedor esta decorado con una gran pecera que es observada por todos los comensales, y a la vista esta la cocina abierta, donde se puede mirar como los cocineros muestran sus habilidades en la preparación de nuestros platos, el servicio de las mesas es sencillo pero muy elegante; los cubiertos, las copas y los detalles de la mantelería así se reúnen todos estos elementos para crear una atmósfera relajante y así obsequiarnos la feliz experiencia de una gran comida. </p>
					<img class="ful" src="'.IMG.'0101/rest.01.jpg" title="SUGOI, fusión franco-japonesa"/>
					<p>En este Restaurante además se ofrece un Sushi bar y Teppan Yaki cada uno en su propio espacio que sin mezclarse, conforman todo el restaurante. </p>
					<p>Quien tiene el crédito por el este resultado tan extraordinario es el Chef Cristian L. Morales cuya carrera de casi veinte años incluye en su paso por las Cocinas de Europa, Estados Unidos y Sur América, teniendo aquí la responsabilidad en el manejo del restaurante SUGOI, y por si no fuera suficiente ya forma parte del selecto club de los diez Chefs mas creativos de México. </p>
					<p>Otro acierto es su ubicación, muy en el centro de la Ciudad y comunicado por amplias avenidas, cita en la Av. Labná SM 20 Mza 9 Lotes 50 y 51.</p>
					<p>No lo pierdan de vista, será en el futuro un gran clásico, el precio no es problema, se paga sin remilgos.</p>'
			),
			'luga' => array(
				'lnk' => '/0101/lugares/la-bodeguita-del-medio-la-casa-del-mojito',
				'tit' => 'La Bodeguita del Medio <br/><small><em> La casa del mojito</em></small>',
				'ttt' => 'La Bodeguita del Medio La casa del mojito',
				'cnt' => '
					<p>En la calle Empedrado, que se localiza en el mismo corazón de la Habana Vieja, nominada por la UNESCO como Patrimonio de la humanidad, está La Bodeguita del Medio, lugar frecuentado por Ernest Hemingway. Allí deleitaba su paladar con los famosos Mojitos, un trago de la coctelería cubana preparado con ron, limón, hierba buena, azúcar, agua gaseada y hielo.</p>
					<p>Los años no han podido borrar las huellas de las personalidades que han estado en la cuna del Mojito en Cuba. Fotos, gallardetes, recuerdos de todo tipo y, sobre todo, cientos de miles de firmas en cualquier lugar de la centenaria edificación convierten a La Bodeguita del Medio en una de las atracciones turísticas de la capital cubana. Hay tanto de historia allí que la visita puede constituir un recuerdo para toda la vida.</p>'
			),
			'even' => array(
				'lnk' => '/0101/eventos/sexto-festival-internacional-de-cine-morelia',
				'tit' => 'Sexto Festival Internacional de Cine <br/><small><em>Morelia</em></small>',
				'ttt' => 'Sexto Festival Internacional de Cine Morelia',
				'cnt' => '
					<p>El Festival Internacional de Cine  de Morelia surge de la necesidad de crear un punto de encuentro único  en nuestro país entre los cineastas mexicanos, el público de Michoacán  y la comunidad fílmica internacional. El Festival Internacional de Cine  de Morelia tiene como finalidad establecer un foro en el cual promover  a los nuevos talentos del cine mexicano, presentar su trabajo en el  marco de una amplia gama de propuestas cinematográficas  internacionales, así como difundir la enorme riqueza del estado de  Michoacán.</p>
					<p>El Festival  Internacional de Cine de Morelia A.C. continúa la tradición establecida  por la Jornada de Cortometraje Mexicano, la cual desde 1994 ha  presentado las innovadoras propuestas de jóvenes realizadores mexicanos  en la generosa sede de la Cineteca Nacional y se traslada con nuevos  elementos a una de las ciudades más hermosas de México: Morelia,  declarada Patrimonio Cultural de la Humanidad por la UNESCO.</p>
					<p>Desde  el año 2003, fecha de inicio del festival, se implementó un nuevo  concurso para complementar la renombrada Sección de Cortometraje  Mexicano: la Sección de Documental Mexicano, la cual reafirma nuestro  compromiso con este género de gran vigencia en el cine nacional.</p>
					<p>Estamos  muy orgullosos de consolidar nuestra asociación con la Semana  Internacional de la Críica del Festival de Cine de Cannes. Este espacio  se ha caracterizado por apoyar el trabajo de nuevos cineastas,  incluyendo importantes talentos mexicanos como Alejandro González  Iñárritu, Guillermo del Toro, y más recientemente, Fernando Eimbcke. En  el marco del Festival Internacional de Cine de Morelia se presenta,  desde su primera edición en 2003, una selección de largometrajes que  forman parte del programa de la Semana Internacional de la Crítica del  Festival de Cannes del año en curso, y lo que es aún más importante  para beneficio de los nuevos realizadores mexicanos, esta prestigiada  sección del Festival de Cannes presenta una muestra de los trabajos  ganadores del Festival Internacional de Cine de Morelia.</p>
					<p>Por  otra parte, el Festival Internacional de Cine de Morelia organiza  proyecciones al aire libre, homenajes a destacados cineastas invitados,  retrospectivas dedicadas a maestros del cine mexicano, una muestra de  cortometrajes michoacanos, cortometrajes para niños, estrenos de  largometrajes mexicanos e internacionales, una sección de películas  estadounidenses con temática de migración y cuestiones transnacionales,  un concurso de guión de cortometraje, exposiciones, conferencias y  distintas actividades relacionadas con el cine.</p>'
			),
			'soci' => array(
				'lnk' => '/0101/sociales/miss-venezuela-dayana-mendoza',
				'tit' => 'Miss Venezuela<br/><small><em>Dayana Mendoza</em></small>',
				'ttt' => 'Miss Venezuela Dayana Mendoza',
				'cnt' => '
					<p>El 14 de julio de 2008, Dayana obtuvo el título Miss Universo 2008, durante el certamen que se realizó en Nha Trang, Vietnam. Con este triunfo, Venezuela iguala en el segundo puesto a Puerto Rico en el número de coronas obtenidas como país. En el certamen Miss Universo 2008 explicó que sus gustos eran el &quot;diseño, fotografía, publicidad y surf.&quot; Además ante las preguntas hechas a las concursantes contestó los &quot;Los hombres creen, ellos piensan que la manera más rápida de ir a un punto es ir en linea recta. Las mujeres saben que la manera más rápida de ir a un punto es seguir las curvas y reparar todo lo que sea necesario.</p>
					<p>Finalmente fue coronada con una tiara de 120.000 dólares tras una final con claro acento latinoamericano: los puestos segundo y tercero correspondieron a Miss Colombia, Taliana Vargas, y a Miss República Dominicana, Marianne Cruz Gonzalez; y el quinto a Miss México, Elisa Nájera. Además de obtener variados premios en efectivo y en género, Mendoza pasará su reinado recorriendo el mundo para dar conferencias sobre cuestiones humanitarias para recoger fondos para promover la educación para luchar contra la enfermedad del sida-VIH.</p>
					<p>Dayana, sobrevivió a un secuestro en 2007 y declaró que el trauma le enseñó a mantener la calma bajo presión. El 5 de agosto de 2008 se dieron a conocer imágenes donde aperece en un desnudo artístico como parte de una campaña publicitaria de un catálogo de una joyería y una óptica, sin que esto afectara su contrato como Miss Universo.</p>'
			),
		);
	}
	
	function cont($section){
		
	}
	
	function index($idx=false, $num=false, $sec=false, $sub=false){
		$cont['cont'] = $this->cont;
		$cont['sect'] = $this->sect;
		
		Load::view('test.html',$cont);
	}
}