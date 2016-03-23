<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
    <h3 class="page-title">
        CMS <small>dokumentáció</small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="admin/home">Kezdőoldal</a> 
                <i class="fa fa-angle-right"></i>
            </li>
            <li><a href="#">Dokumentáció</a></li>
        </ul>
    </div>
    <!-- END PAGE TITLE & BREADCRUMB-->
    <!-- END PAGE HEADER-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">


            <h3><legend>Multijob CMS dokumentáció</legend></h3>



            <div class="tab-pane" id="tab_1_3">
                <div class="row profile-account documentation">
                    <div class="col-md-3">
                        <ul class="ver-inline-menu tabbable margin-bottom-10">
                            <li class="active">
                                <a data-toggle="tab" href="#tab_1-1"><i class="fa fa-cog"></i>A CMS-ről</a> 
                                <span class="after"></span>                                    
                            </li>
                            <li ><a data-toggle="tab" href="#tab_2-2"><i class="fa fa-list"></i> Munkák listája</a></li>
                            <li ><a data-toggle="tab" href="#tab_3-3"><i class="fa fa-edit"></i> Munka felvitele</a></li>  
                            <li ><a data-toggle="tab" href="#tab_4-4"><i class="fa fa-file-text-o"></i> Munka kategóriák</a></li> 
                            <li ><a data-toggle="tab" href="#tab_5-5"><i class="fa fa-book"></i> Munkaadók</a></li>   
                            <li ><a data-toggle="tab" href="#tab_6-6"><i class="fa fa-files-o"></i> Oldalak szerkesztése</a></li>
                            <li ><a data-toggle="tab" href="#tab_7-7"><i class="fa fa-font"></i> Meta adatok</a></li>
                            <li ><a data-toggle="tab" href="#tab_8-8"><i class="fa fa-pencil"></i> WYSIWYG szerkesztő</a></li>

                            <li ><a data-toggle="tab" href="#tab_9-9"><i class="fa fa-users"></i> Felhasználók</a></li>
                            <li ><a data-toggle="tab" href="#tab_10-10"><i class="fa fa-picture-o"></i> Képek kezelése</a></li>
                            
                            <li ><a data-toggle="tab" href="#tab_11-11"><i class="fa fa-suitcase"></i> Modulok</a></li>
                            <li ><a data-toggle="tab" href="#tab_12-12"><i class="fa fa-cogs"></i> Beállítások</a></li>
                        </ul>
                    </div>

                    <div class="col-md-9">
                        <div class="tab-content">
                            
<!-- ****************************** A CMS RENDSZERRŐL ***************************** -->                                
                            <div id="tab_1-1" class="tab-pane active">
                                <h3>Multijob tartalomkezelő rendszer (CMS)</h3>

                                <p>A weblap funkcióinak megfelelően kialakított adminisztrációs felületet lehetőséget biztosít a weblap tartalmának karbantartásához, frissítéséhez. Használatához nem szükséges szakirányú ismeretekkel rendelkezni, bárki könnyen megtanulhatja a rendszer kezelését.</p>
                                <h4><i class="fa fa-chevron-circle-right"></i> A tartalomkezelőrendszer funkció</h4>
                                <ul>
                                    <li>Munkák kezelése</li>
                                    <li>Munka kategóriák kezelése</li>
                                    <li>Munkaadók kezelése</li>
                                    <li>Előregisztráltak (adatlapot kitöltő felhasználók) kezelése</li>
                                    <li>Regisztráltak / hírlevélre feliratkozottak kezelése</li>
                                    <li>Oldalak tartalmának szerkesztése</li>
                                    <li>Felhasználók kezelése (az adminisztrációs rendszer felhasználói)</li>
                                    <li>Irodák kezelése</li>
                                    <li>Modulok
                                        <ul>
                                            <li>Kezdőoldali slider (képváltó) szerkesztése</li>
                                        </ul>
                                    </li>    
                                    <li>Fájlkezelő</li>
                                    <li>Beállítások</li>
                                    <li>Dokumentáció</li>
                                </ul>
                             </div>

<!-- ****************************** NGATLANOK LISTÁJA ***************************** -->                                
                            <div id="tab_2-2" class="tab-pane">
                                <h3>Munkák listája</h3>
                                
<p>Az adminisztrációs rendszerben felvitt munkák listázása lehetővé teszi a munkák áttekintését (a főbb jellemzők alapján), valamint különféle műveletek végrehajtását a munkákkal. A listázáshoz beállítható (listából kiválasztva), hogy egy oldalon mennyi munka jelenjen meg, és a "kereső sor" pedig lehetővé teszi a listában megjelenített elemekben történő keresést.</p>
<p>A lista fejlécében található háromszögekre kattintva a lista az oszlopok szerint (nem mindegyik alapján) sorba rendezhető. </p>
                                
<img src="<?php echo ADMIN_IMAGE; ?>munka_lista.jpg" class="img-thumbnail">                        

<h4><i class="fa fa-chevron-circle-right"></i> Műveletek a munkákkal</h4>
<p>Az ingatlan lista utolsó oszlopában található fogakkerés ikonra kattintva megjelenik az adott ingatlannal végezhető műveletek listája.</p>

                                <ul>
                                    <li><strong>Részletek:</strong> a munka valamennyi adata megtekinthető</li>
                                    <li><strong>Szerkesztés:</strong> a rögzített adatok módosítása</li>
                                    <li><strong>Törlés:</strong> a művelet véglegesen törli a munkát az adatbázisból</li>
                                    <li><strong>Blokkolás / aktiválás:</strong> a blokkolt munka inaktív státuszba kerül, ami azt jelenti, hogy nem jelnenik meg a front oldalon, de nem törlődik az adatbázisból. A blokkolt munka újra aktív állapotba állítható.</li>    
                                    
                                </ul>

<h4><i class="fa fa-chevron-circle-right"></i> Szűrés</h4>

                                <p>A szűrés sáv használatával szűkíthető a megjelenített munkák listája, és egyedileg munkára is lehet keresni. A szűrési feltételek a "x" gombra kattintva törölhetők: az űrlap tattalma törlődik, és a szűrés nélküli találati lista töltődik be (az "újratöltés" gombra kattintás ugyanezt eredményezi).</p>
                                <p>Id-re történő kereséskor csak a számot kell beírni (# nélkül), a munka megnevezésére keresve bármilyen szöveg beírható (a találati listában azok a munkák is szerepelni fognak, amelyekre csak részleges az egyezés). </p>
                                <p>A szűrési feltételek ÉS kapcsolatban vannak egymással, vagyis azok a munkák fognak a találati listában mszerepelni, amelyek valamennyi szűrési feltételenek megfelelnek.</p>    
                                
                                <p>Csoportos műveletek: az első oszlop jelölő négyzeteivel jelölhetők be azok a munkák, amelyekre csoportos műveletet szeretnénk végrehajtani. A munkák kiválasztása után a "csoportművelet" gomb melletti listából ki kell választani az alkalmazandó műveletet.</p>    
                                
                            

                             </div> 

<!-- ****************************** NGATLANOK FELVITELE ***************************** -->                                
                            <div id="tab_3-3" class="tab-pane">
                                <h3>Munkák felvitele</h3>

                                <p>Új munka hozzáadása a munkák menüben, az új munka hozzáadása almenüben történik. Ugyanez a felület az munkák listája oldalról, az „Új munka” gombra kattintva is elérhető. Az adatfelvitel mentés előtt bármikor megszakítható, a „mégse” gombra kattintás után a „munkák listája” töltődik be.</p>
                                <p>A piros csillaggal jelölt mezők kitöltése kötelező. Amennyiben úgy történik a mentés, hogy nincs minden kötelező mező kitöltve, hibaüzenet figyelmeztet a nem kitöltött mezőkre.</p>
                                                                  
<img src="<?php echo ADMIN_IMAGE; ?>uj_munka.jpg" class="img-thumbnail">                             
                                
                                <h4><i class="fa fa-chevron-circle-right"></i> Adatok</h4>                                   
<p>A referens mezőbe automatikusan a bejelentkezett felhasználó neve kerül. A szuperadminisztrátor más referenst (felhasználót) is választhat, így más nevében vihet fel munkát. Az admin jogosultságú felhasználó csak a saját nevében rögzíthet munkát.</p>
                              <p>A megnevezés mező a munka rövid elnevezésére szolgál (kötelező kitölteni), a leírásba a részletek kerülnek. A munkához kapcsolódó feltételek megadásához külön mező szolgál.</p>
                              <p>A munka kategóriák listájából a már rögzített kategóriák közül lehet választani (megadása kötelező).</p>

                              <p>A fizetés és munkaidő mezőkbe szöveges módon lehet az adatokat rögzíteni.</p> 
                              <p>A megye kiválasztása után megjelenik a város kiválasztása lista, Budapestet választva pedig a kerület lista is megjeleneik. A megye és város kiválasztása kötelező.</p>
                              
                              <p>A munkát kínáló cég a cégek listájából választható ki, amennyiben a céget előzőleg a munkaadók közé felvettük. Megadása nem kötelező.</p>
                              <p>A munkához kapcsolódó egyedi feltételek a feltételek mezőben rögzíthetők.</p>
                              <p>A munka lejáratának dátuma alapbeállításként 14 nap, így ha nem adunk meg lejáratot, akkor ez kerül be az adatbázisba. Lejárat kiválasztásakor maximum két hómnapos lejárat választható ki. </p>
                              
                              <p>A státusz kiválasztásával inaktív vagy aktív állapotban menthető el a munka.</p>                                  
                              
                             
                             </div> 

<!-- ****************************** KÉPEK FELTÖLTÉSE ***************************** -->                                
                            <div id="tab_4-4" class="tab-pane">
                                <h3>Munka kategóriaák</h3>
                                
<p>A munkákhoz kapcsolódó kategóriák a "munka kategóriák" menüpont alatt tekinthetők meg. A lista a munkák kategóriánkénti számát is mutatja. A kategóriák szerkeszthetők, és új kategória is létrehozható.</p>

<p>A kategóriákhoz képet lehet feltölteni. A feltöltés során a rendszer a képet a megfelelő méretekre konvertálja (300x200 képpont, valamint egy kisebb méretű bélyegkép), és feltölti őket a szerverre, a megfelelő mappába. A torzízásmentes megjelenítés érdekében ügyelni kell arra, hogy a feltöltésre kiválasztott kép 3:2 képarányú (vagy ahhoz közeli) legyen. A kép mellett még a kategória nevét lehet megadni / módosítani.</p>


                             </div>

<!-- ****************************** KÉPEK FELTÖLTÉSE ***************************** -->                                
                            <div id="tab_5-5" class="tab-pane">
                                <h3>Munkaadók</h3>
                                
<p>A munkák rögzítésekor kiválasztható (listából) a munkát kínáló cég. Ezek a cégek a munkaadók menüpontban rögzíthetők. A munka felvitelekor a munkaadó megadása nem kötelező (és a munkaadóra vonatkozó információk nem nem jelenenk meg a front oldalon), de a cégek adatainak felvitele segíti a munkák listájában a cég szerinti hatékony keresést. Emellett hasznos lehet, hogy gyorsan megekereshetők a munkát kínáló cégek fontosabb kontakt adatai. A munkaadók listájában néhány adat mellett a cégek által kínált, a rendszerben rögzített munkák száma is megjelenik.</p>

<p>Új munkaadó rögzítéséhez a fontosabb kontakt adatokat (a cég neve, címe, kapcsolattartó, telefonszám, e-mail cím) lehet rögzíteni, valamint megjegyzés is fűzhető a cégekhez. A cég státusza lehet aktív és inaktív, az inaktív cégek nem jelennek meg munka felvitelekor a munkaadók listájában).</p>
                             </div>

<!-- ****************************** Oldalak szerkesztése ***************************** -->									
                            <div id="tab_6-6" class="tab-pane">

                                <h3>Oldalak szerkesztése</h3>
                                <p>A weblap oldalai tartalmának szerkesztése az Oldalak menüben, az Oldalak listája menüpont alatt érhető el. A listában a szerkesztés gombra kattintva jelenik meg a szerkesztő felület, amelyen az úgynevezett meta adatok (title, description, keywords - részletes információ a <strong>meta adatok</strong> részben) és a tartalom szerkeszthető. Ez utóbbi szerkesztése egy WYSIWYG szerkesztő felületen történik (részletes információ a <strong>WYSIWYG szerkesztő</strong> részben).     

                            </div>
                            <!-- ****************************** Meta adatok ***************************** -->									
                            <div id="tab_7-7" class="tab-pane">

                                <h3>Oldalak szerkesztése - meta adatok megadása</h3>

                                <h4><i class="fa fa-chevron-circle-right"></i> Title, description és keywords megadása</h4>
                                <p>A szerkeszthető oldalak esetében megadhatók a title, description és keywords meta adatok, amelyek a weboldalon közvetlenül nem láthatók, de van funkciójuk. A title a keresőoptimalizálás terén fontos elem, a jól megfogalmazott description növelheti a kattintási arányt a Google találati listájában, a keywords azonban a Google esetében már nem bír jelentőséggel, így ez az elem akár üresen is hagyható. </p>
                                <h4><i class="fa fa-chevron-circle-right"></i> Title (az oldal címe)</h4>
                                <p>A title (amennyiben az oldal tartalmához reveleváns módon van megfogalmazva) képezi a Google találati listájában megjelenő címet, és ez jelenik meg a böngésző füleken is címként. 
                                <p>Az oldal címét (title) az alábbi szempontok szerint érdemes összeállítani:</p>
                                <ul>
                                    <li>Szerkezet:  A title elején legyen a kulcsszó (kulcsszó kifejezés), amire az adott oldalt szeretnénk optimalizálni. </li>
                                    <li>Hossz: a title ne legyen hosszabb 70 karakternél, de ne is legyen nagyon rövid.</li>
                                    <li>Minden title különböző legyen</li>
                                    <li>Ne legyen a kulcsszó többször ismételve</li>

                                </ul>
                                <h4><i class="fa fa-chevron-circle-right"></i> Description (leírás)</h4>
                                <p>A description a weboldal tartalmát írja le a title-nél kicsit hossszabban. Amennyiben a description releváns a weboldal tartalmához, a Google a találati listában a cím alatt az oldal description elemét jeleníti meg leírásként, ellenkező esetben a weboldal szövegéből választ a Google algoritmusa szövegrészleteket. A jó leírás növelheti a kattintási arányt, de keresőoptimalizálási szempontból nincs közvetlen jelentősége.</p>  
                                <h4><i class="fa fa-chevron-circle-right"></i> Keywords (kulcsszavak)</h4>
                                <p>A weboldalra jellemző kulcsszavakat lehet a keywords meta elemben elhelyezni. Mivel korábban manipulatív céllal használták, ezért a Google algoritmusa már nem veszi figyelembe.</p> 
                            </div>                                

                            <!-- ****************************** WYSIWYG szerkesztő ***************************** -->									
                            <div id="tab_8-8" class="tab-pane">

                                <h3>WYSIWYG szerkesztő</h3>
                                <p>Az úgynevezett WYSIWYG  (What You See Is What You Get = Amit lát, azt kapja) szövegszerkesztő segítségével, a Word-höz hasonlóan formázhatja meg a szöveget, vagy szúrhat be képeket, YouTube videókat, vagy HTML elemeket. A mentés után a weboldalon máris a módosított tartalom érhető el. A WYSIWYG szerkesztők célkitűzése egy olyan felületet biztosítása a felhasználók számára, amelyen keresztül vizuálisan lehet elkészíteni a formázott szöveget, akár a HTML nyelv ismerete és a forráskód szerkesztése nélkül is. </p>
                                <p>Nem szükséges tehát a HTML nyelv ismerete a weblap tartalmának módosításához, a szöveges tartalmakat, képeket, beágyazott videókat egyszerűen szerkesztheti. A forráskód nézetben a komolyabb szerkesztési műveletek is elvégezhetők, de ehhez nem árt némi HTML ismerettel rendelkezni. </p>
                                <img src="<?php echo ADMIN_IMAGE; ?>wysiwyg_editor_1.jpg" class="img-thumbnail">

                                <h4><i class="fa fa-chevron-circle-right"></i> A WYSIWYG szerkesztő előnyei</h4>
                                <ul>
                                    <li>Áttekinthető tartalom: a szerkesztő nagyjából helyesen mutatja a tartalmat, ahhoz hasonlóan, ahogy az a weboldalon meg fog jelenni.</li>
                                    <li>A tartalom szerkesztésekor azonnal látható az eredmény, a WYSIWYG szerkesztő az eredményhez közeli állapotot mutatja a HTML forrás helyett</li>
                                    <li>A tartalom egyszerű szerkesztése: a megfelelő elemre kattinthatva azt átszerkeszthetjük, míg a forráskód esetén nem kis ügyességet igényel a forráskód vonatkozó részét tévedés nélkül megtalálni és módosítani.</li>                                    
                                </ul> 
                                <p>
                                    <span class="badge badge-danger">FIGYELEM</span>
                                    <span>A módosítások elmentése után nincs lehetőség visszaállításra, ezért a mentést célszerű körültekintéssel végezni!</span>
                                </p>
                                <p>
                                    <span class="badge badge-info">INFO</span>
                                    <span>A WYSIWYG szerkesztő nem profi HTML szerkesztő alkalmazás, ezért csak kellő tapasztalattal és HTML ismeretekkel érdemes a forráskódot szerkeszteni!</span>
                                </p>

                                <h4><i class="fa fa-chevron-circle-right"></i> Mire kell figyelni?</h4>
                                <ul>
                                    <li>Formázás alkalmazása: a szerkesztőben elvégzett formázások módosítják a weblap megjelenését, felülírják a weblaphoz készített úgynevezett stíluslap utasításait. Formázás alkalmazásával eltűnhetnek az eredetileg alkalmazott stílusok.</li>
                                    <li>Lehetőleg ne módosítsa a betűtípust! A módosított betűtípus helyesen (ékezetekkel) csak akkor fog a weblapot meglátogató felhasználó számára megjelenni, ha annak magyar ékezetes készlete telepítve van a felhasználó gépére. A különöző, nem összeillő betűtípusok az egységes dizánt rontják, amatőr hatást keltenek.</li>
                                    <li>Képek beszúrásakor, módosításakor a kép feltöltése eredeti méretben történik, ezért ügyelni kell arra, hogy ne nagyméretű képeket töltsön le a böngésző, mivel a nagy fájlméret lassítja a weboldal betöltését. </li>                                    
                                </ul> 
                                <h4> <i class="fa fa-chevron-circle-right"></i> Képek beszúrása, módosítása</h4>
                                <p>A weboldal szöveges tartalmába képek szúrhatók be, illezve a meglévő képek helyett más képek helyezhetők el a dokumentumban..</p>
                                <p>Kép beszúrás folyamata:</p>
                                <ul>
                                    <li>A szekesztő ikon sorában kattintson a kép ikonra, vagy kép módosításakor kattintson kétszer a képre.</li>
                                    <li>A kép tulajdonságai ablakban kattintson a "böngészés a szerveren" gombra.</li>
                                    <li>A külön ablakban megnyíló fájlkezelőben dupla kattintással válassza aki a kívánt képet, vagy a feltöltés gombra kattintva töltsön fel új képet, majd azt válassza ki (a szerkesztőben feltöltött képek az uploads/images mappába kerülnek).</li> 
                                    <li>A kép tulajdonságai ablakban megjelenik a kiválasztott kép elérési útvoanala (hivatkozás) valamint az előnézete. Amennyiben megjelennek a szélesség és magasság rubrikákban a kép méretei, törölje ki azokat, mivel a méret megadása tönkre teszi a reszponzív megjelenítést.</li> 
                                </ul> 
                                <img src="<?php echo ADMIN_IMAGE; ?>kep_beszuras.jpg" class="img-thumbnail">                                    





                            </div>


                            <div id="tab_9-9" class="tab-pane">
                                <h3>Felhasználók</h3>

                                <h4><i class="fa fa-chevron-circle-right"></i> Az adminisztrációs rendszerhez hozzáféréssel rendelkező felhasználók kezelése</h4>
                                <p>A felhasználók menüben - jogosultságtól függően - a következő funkciók érhetők el:
                                <ul>
                                    <li>Felhasználók listázása</li>
                                    <li>Új felhasználó létrehozása (csak szuperadmin hozhat létre felhasználót, szuperadmin vagy admin jogosultsággal</li>
                                    <li>Felhasználó törlése (egyedi vagy csoportos törlés, szuperadmin nem törölhető, admin nem törölhet admin jogosultságú felhasználót)</li> 
                                    <li>Felhasználó státusz (aktív / inaktív) módosítása (szuperadmin nem tehető inaktívvá). Az inaktív felhasználó nem léphet be az adminisztrációs rendszerbe. </li> 
                                    <li>Felhasználói profil módosítása. A bejelentkezett felhasználó módosíthatja adatait.</li> 
                                </ul>    
                                <p>Az adminisztrációs rendszerben a felhasználók felhasználói csoportokba tartoznak. A egyes felhasználói csoportok különböző jogosultságokkal rendelkeznek, és ennek megfelelően eltérő adminisztrációs felületet (funkciókat) érhetnek el. </p>
                                <h4><i class="fa fa-chevron-circle-right"></i> Felhasználói jogosultságok</h4>        
                                <p>Kétféle jogosultság létezik az adminisztrációs rendszerben: szuperadminisztrátor (szuperadmin) és adminisztrátor (admin). A szuperadmin létrehozhat új felhasználót, és törölhet admin jogosultságú felhasználót. Az admin nem hozhat létre és nem törölhet felhasználót. A szuperadmin bármelyik felhasznál nevében rögzíthet munkát, míg az admin csak a saját nevében teheti ezt meg. Egyéb tekintetben megegyeznek a felhasználói jogosultságok. </p>

                            </div>

                            <!-- ****************************** Képek kezelése, feltöltése ***************************** -->							
                            <div id="tab_10-10" class="tab-pane">
                                <h3>Képek kezelése, feltöltése </h3>


                                <h4><i class="fa fa-chevron-circle-right"></i> Képek feltöltése a különböző modulokban (pl.:felhasználók, slider, képgaléria)</h4>

                                <p>A képfeltöltésnél a "kiválasztás" gombra kattintva lehet feltöltésre képet kiválasztani. Kiválasztás után megjelenik egy "módosít" és egy "töröl" elnevezésű gomb. A módosít gombra kattintva választható ki másik kép, a töröl gombbal pedig „resetelhető”  a kiválasztás. A kép feltöltése (és méretezése) ténylegesen a hozzá kapcsolódó űrlap elmentésekor történik meg.</p>
                                <p>A felhasználókhoz, valamint az egyes modulokban feltöltött képeket a rendszer a megfelelő méretben tölti fel a szerverre (általában egy kisebb és egy nagyobb méretben), és az UPLOADS mappa megfelelő almappáiba kerülnek.   
                                <p>
                                
                                <span class="badge badge-danger">FIGYELEM</span>
                                <span>A feltöltött képek fájlnevét ne módosítsa, mivel  a képek elérését a rendszer adatbázosban tárolja, így azok a képek, amelyeknek a nevét módosítja, elérhetetlenné válnak! Ne módosítsa a képek méretét sem!</span>
                                

                                <h4><i class="fa fa-chevron-circle-right"></i> Képek kezelése a WYSIWYG szerkesztőben</h4>

                                <p>A WYSIWYG szerkesztő a képfeltöltéskor és beillesztéskor nem „csinál semmit” a képpel, vagyis az eredeti képméretben töltődik fel a szerverre ( a html dokumentumba a képre való hivatkozás kerül be). Ezért érdemes a feltöltés előtt a kép méretét optimalizálni (pl. egy 3000 pixel széles képet elég maximum 600-700 pixeles méretben feltölteni). Az optimalizálás feltöltés után is végrehajtható. Erre használható az admin rendszer  fájlkezelője is, de ez viszonylag nagy képméretet produkál.</p>    
                                <p>A WYSIWG szerkesztőben feltöltött képek az uploads/images mappába kerülnek. </p>
                                <p>A szerkesztőben történő kép beszúrásról részletesebb információ a <strong>WYSIWYG szerkesztő</strong> részben található.</p> 

                                <h4><i class="fa fa-chevron-circle-right"></i> Az admin rendszer fájlkezelője</h4>

                                <p>Az adminisztrációs rendszer fájlkezelője lehetővé teszi az UPLOADS mappában található képek (vagy más típusú fájlok) kezelését. A képek másolhatók, törölhetők, átnevezhetők, valamint módosítható a méretük, kivághatók, illetve elforgathatók. </p>
                                <p>Az IMAGES (a WYSIWYG szerkesztőben feltöltött képek kerülnek ide) mappa kivételével a képek a rendszer által megszabott méretben és néven kerülnek a szerverre, ezeket a képeket ezért nem ajánlatos bármilyen módon módosítani.</p>
                                <p>Az IMAGES mappába feltöltött képek esetében csak arra kell figyelni, hogy ne változzon meg a kép neve.</p>
                            </div>

      <!-- ****************************** Modulok ***************************** -->

                            <div id="tab_11-11" class="tab-pane">
                                <h3>Modulok kezelése</h3>


                                <h4><i class="fa fa-chevron-circle-right"></i> Kezdőoldali slider</h4>

                                <p>A kezdőoldalon megjelenő slider (képváltó) szerkeszthető. A slider több slide-ból áll, az egyes slide-ok pedig a következő elemekből állnak: 
                                <ul>    
                                    <li>kép</li> 
                                    <li>cím</li> 
                                    <li>szöveg</li>
                                    <li>link</li>
                                    <li>státusz</li> 
                                </ul>  
                                <p>A slide-hoz tartozó kép akkor lesz optimális (torzításmentes és a lehető legjobb minőségű), ha 1170X420 képpont méretű képet tölt fel. A rendszer ugyanis ilyen méretre kovertálva tölti fel a kiválasztott képet. Ha a kiválasztott kép méretaránya ettől eltérő, vagy kisebb méretű, akkor a kép torzítva (összenyomva, széthúzva), vagy nagyítás esetén gyengébb képminőségben jelenik meg.</p>
                                <p> Az inaktív státuszú slide-ok elérhetők az adatbázisban, de nem jelennek meg a slider-ben. Minden slide szerkeszthető, törölhető, és új slide is létrehozható. Szerkesztéskor módosítható minden paraméter: a kép, a cím, a szöveg és a státusz.</p>
                                <p>A megjelenés sorrendje is módosítható. Módosításhoz mozgassa a kurzort a fölé a slide fölé, amelynek a sorrendjét módosítani kívánja. A slide fölött a kurzor négy irányú nyíl formára vált, ekkor tudja a slide-ot a bal egér gomb lenyomása mellett mozgatni. Mozgassa a slide-ot a kívánt pozícióba, majd engedje el az egér gombját. Az új sorrend elmentése automatikusan megtörténik. </p> 

                                                                                                                                   

                            </div>
      
         <!-- *********************** BEÁLLÍTÁSOK ************************* -->
                            <div id="tab_12-12" class="tab-pane">
                                <h3>Beállítások</h3>

                                <p>A beállítások menüben a következő adatok módosíthatók:</p>
                                <ul>
                                    <li>Cégnév - a weblap különböző helyein (kapcsolat, footer) megjelenő cégnév</li>
                                    <li>Cím - a weblap különböző helyein (kezdőoldal, kapcsolat, footer) megjelenő cím.</li>
                                    <li>Általános e-mail cím - a weblap különböző helyein (kezdőoldal, kapcsolat, footer) megjelenő e-mail. Erre az e-mail címre érkezik a weboldalról küldött üzenetek egy része.</li>
                                    <li>Telefon - a weblap különböző helyein (kezdőoldal, kapcsolat, footer) megjelenő központi telefonszám</li>
                                   
                                    <li>Diák e-mail cím: a diákok által, a weblapról küldött e-mail üzenetek erre a címre érkeznek</li>
                                    <li>Céges e-mail cím: a cégek által, a weblapról küldött e-mail üzenetek erre a címre érkeznek</li>
                                    <li>Facebook link: a Facebook oldal linkje (pl.: https://www.facebook.com/afacebookoldalneve)</li>                                        
                                </ul>

                                <p>A beállításban tárolt adat módosítása után a weblapon az illető adat minden helyen automatikusan módosulni fog. </p>    

                            </div>                                


                        </div> <!--END TAB-CONTENT-->
                    </div> <!--END COL-MD-9--> 
                </div> <!--END ROW PROFILE-ACCOUNT-->
            </div> <!--END TAB-PANE-->


        </div> <!-- END COL-MD-12 -->
    </div> <!-- END ROW -->	

</div> <!-- END PAGE CONTAINER-->