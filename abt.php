<!DOCTYPE html>
<html>

<head>
    <title>Food Scanner</title>
    <link rel="stylesheet" href="styles2.css">
</head>

<>
<header>
        <div class="top-container">
            <ul class="myUL">
                <li><a href="try2.php">
                        <h5>Home</h5>
                    </a></li>
                <li><a href="account.php">
                        <h5>Account</h5>
                    </a></li>
                <li><a href="aboutus.php">
                        <h5>About Us</h5>
                    </a></li>
                <li><a href="allerinfo.php">
                        <h5>Learn more</h5>
                    </a></li>
                <li><a href="abt.php">
                        <h5>Details</h5>
                    </a></li>
                    <li><a href="images\26p23e0039_รายงานฉบับสมบูรณ์ 4.pdf" download="Allergy_paper.pdf">
                    <h5>Paper</h5>
                    </a></li>
            </ul>
        </div>
    </header>
    <section class="first">
        <h1 class="Topic" id="what-is">What is <br> Food Scanning Programme For Food Allergies</h1>
    </section>
    <section class="flex-hori">
        <div class="column-white" id="what-is-text"><img src="images\Asset 3@4x.png" alt="Flow" class="round-border">
        </div>
        <div class="column-white" id="what-is-text">
            <p class="about-text" id="what-is-dess">
                <span class="tab-space"></span>Food Scanning Programme For Food Allergies
                เป็นโปรแกรมแสกนส่วนประกอบของอาหารพร้อมระบุกลุ่มการแพ้อาหารมีเบื้องหลังการทำงานด้วย Machine learning
                model YOLOv8 ที่มีรายละเอียดการทำงานตามแผนภาพทางด้านซ้ายนี้ โดยใช้การทำงานในระบบ API based
                หากสนใจข้อมูลสามารถโหลดเอกสารเพิ่มเติมได้บริเวณแถบขวามสุดด้านบนสุดของหน้าเว็ป <br>
                <br>โดยการตรวจจับอาหารของโมเดลจะได้ผลลัพธ์ออกมาภายในคลาสต่อไปนี้
            </p>
            <ul id="class-ul">
                <li>candy</li>
                <li>egg tart</li>
                <li>french fries</li>
                <li>chocolate</li>
                <li>biscuit</li>
                <li>popcorn</li>
                <li>pudding</li>
                <li>ice cream</li>
                <li>cheese butter</li>
                <li>cake</li>
                <li>wine</li>
                <li>milkshake</li>
                <li>coffee</li>
                <li>juice</li>
                <li>milk</li>
                <li>tea</li>
                <li>almond</li>
                <li>red beans</li>
                <li>cashew</li>
                <li>dried cranberries</li>
                <li>soy</li>
                <li>walnut</li>
                <li>peanut</li>
                <li>egg</li>
                <li>Fruit</li>
                <li>Meat</li>
                <li>sausage</li>
                <li>sauce</li>
                <li>crab</li>
                <li>fish</li>
                <li>shellfish</li>
                <li>shrimp</li>
                <li>soup</li>
                <li>bread</li>
                <li>corn</li>
                <li>hamburg</li>
                <li>pizza</li>
                <li>hanamaki baozi</li>
                <li>wonton dumplings</li>
                <li>pasta</li>
                <li>noodles</li>
                <li>rice</li>
                <li>pie</li>
                <li>tofu</li>
                <li>Vegetable</li>
                <li>Mushroom</li>
                <li>salad</li>
                <li>other ingredients</li>
                <li>olives</li>
            </ul>
        </div>
    </section>
    <section class="flex-hori">
        
        <div class="column-white" id="what-is-text">
            
        <h3>Function: Search Allergies</h3>
            <p class="about-text" id="what-is-dess">
                <span class="tab-space"></span>ฟังก์ชันที่ผู้ใช้งานสามารถกรอกชื่อเมนูอาหาร จะแสดง
                ข้อมูลว่าผู้แพ้อาหารกลุ่มใดบ้าง ที่ไม่สามารถรับประทานเมนูนี้ได้ หากได้ล็อกอินบัญชีผู้ใช้แล้วผู้ใช้แพ้เป็นหนึ่งในกลุ่มผู้แพ้ที่ควรหลีกเลี่ยงอาหารนี้
                ระบบจะมีการเน้นย้ำ ตัวอย่างการค้นหาดังภาพ ในกรณีนี้ผู้ใช้มีการแพ้ที่ไม่ตรงกับส่วนประกอบที่มีในเมนูที่เสริช จึงแสดงข้อมูลปกติ
                ซึ่งประเภทอาหารที่แพ้ได้ในส่วนนี้จะเป็นข้อมูลที่ละเอียดมากยิ่งขึ้น 
                <br>โดยมีClass การแพ้ถึง 30 Classes ดังนี้
            </p>
            <ul id="class-ul">
                <li>diary</li>
                <li>gluten</li>
                <li>wheat</li>
                <li>egg</li>
                <li>milk</li>
                <li>peanut</li>
                <li>tree nut</li>
                <li>soy</li>
                <li>fish</li>
                <li>shellfish</li>
                <li>pork</li>
                <li>red meat</li>
                <li>crustacean</li>
                <li>celery</li>
                <li>mustard</li>
                <li>sesame</li>
                <li>lupine</li>
                <li>mollusk</li>
                <li>alcohol</li>
                <li>sulphite</li>
            </ul>
        </div>
        <div class="column-white" id="what-is-text"><img src="images\search.jpg" alt="Flow" class="round-border">
        </div>
    </section>
    
    <section class="banner" id="development">
        <h1>Undergoing Development</h1>

    </section>
    <section>
        <div class="column">
            <div class="member">
                <img src="images\Food-101-dataset-Activeloop-Platform-visualization-image-1024x455.png"
                    alt="Image of Dataset Expansion" class="round-border">
                <h2>Dataset Expansion</h2>
                <p class="about-text">
                    <span class="tab-space"></span>เนื่องจากตอนนี้ Dataset
                    ที่เราใช้ส่วนใหญ่จะเป็นภาพอาหารต่างประเทศทำให้มีข้อจำกัดในการใช้งาน
                    ในการแสกนภาพอาหารถิ่นพวดเราจึงมีแผนที่จะขยายขอบเขตของ Dataset
                    ที่ใช้ในการเรียนรู้ของโมเดลโดยมีแผนจะรวบรวมอาหารถิ่นของประเทศไทย และประเทศอื่น ๆ ในลำดับต่อไป
                </p>
            </div>
        </div>
        <div class="column">

            <div class="member">
                <img src="images\F1_curve.png" alt="Image of Model Accuracy" class="round-border">
                <h2>Model Accuracy</h2>
                <p class="about-text">
                    <span class="tab-space"></span>เรากำลังพัฒนาความแม่นยำของโมเดลของเราให้มีประสิทธิภาพ
                    และเพื่อให้ผู้ใช้มีประสบการณ์การการใช้งานที่ดีที่สุดมีความผิดพลาดที่เกิดขึ้นน้อยที่สุดโดยทำได้โดยการหา
                    Dataset
                    ที่มีขนาดใหญ่ขึ้นและคุณภาพสูงขึ้นซึ่งกระบวนการทั้งหมดนี้ใช้เวลาและทางทีมพัฒนากำลังพยามอย่างเต็มที่เพื่อพัฒนาขีดความสามารถของ
                </p>
            </div>
        </div>
        <div class="column">

            <div class="member">
                <img src="images\blank-profile-picture-973460_960_720.png" alt="Image of Customization"
                    class="round-border">
                <h2>Customization</h2>
                <p class="about-text">
                    <span class="tab-space"></span>เรากำลังพัฒนาระบบ customize เพื่อให้ผู้ใช้สามารถปรับแต่งหน้า web
                    ปรับปรุงข้อมูลส่วนตัวการจัดเก็บข้อมูลและระบบเกี่ยวกับ account รวมถึงระบบ easter egg อื่น ๆ ด้วย
                </p>
            </div>
        </div>
    </section>
    <footer>
        <h2>contact us</h2>
        <p>Email: nscprojectstorage@gmail.com<br>Tel: 0929989812</p>
        <div id="disclaimer">
            <h2>Disclaimer</h2>
            <p>Agreement
                This software is a work developed by Adulvitch Kajittanon, Thanakrit Damduan and Phakthada Pitavaratorn
                from Kamnoetvidya Science Academy (KVIS) under the provision of Dr.Kanes Sumetpipat under Program for
                food allergy warning in food allergy which has been supported by the National Science and Technology
                Development Agency (NSTDA), in order to encourage pupils and students to learn and practice their skills
                in developing software. Therefore, the intellectual property of this software shall belong to the
                developer and the developer gives
                NSTDA a permission to distribute this software as an “as is” and non-modified software for a temporary
                and non-exclusive use without remuneration to anyone for his or her own purpose or academic purpose,
                which are not commercial purposes. In this connection, NSTDA shall not be responsible to the user for
                taking care, maintaining, training, or developing the efficiency of this software. Moreover, NSTDA shall
                not be liable for any error, software efficiency and damages in connection with or arising out of the
                use of the software.</p>
        </div>
    </footer>
</body>

</html>