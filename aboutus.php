<?php
// Assuming the rating value is being retrieved from a POST request or a database.
$rating_value = isset($_POST['rating']) ? $_POST['rating'] : 5;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Food Scanner</title>
    <link rel="stylesheet" href="styles2.css">
</head>

<body>
    <header>
        <div class="top-container">
            <ul class="myUL">
                <li><a href="try2.php">
                        <h5>Home</h5>
                    </a></li>
                <li><a href="newlogin.php">
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
                <li><a href="file\NSC_26p23e0039_Report_Final01.pdf" download="Allergy_paper.pdf">
                        <h5>Paper</h5>
                    </a></li>
            </ul>
        </div>
    </header>

    <section class="first" id="about-us">
        <h1>Our Team</h1>
    </section>
    <section>
        <div class="column">
            <div class="member">
                <img src="images/S__18669690.jpg" alt="Image of First member" class="round-border">
                <h2>อดุลย์วิชญ์ ขจิตตานนท์</h2>
                <p class="about-text">
                    <span class="tab-space"></span>"ผม อดุลย์วิชญ์ ขจิตตานนท์ ชื่อเล่นชื่อพัตเตอร์ครับ ตอนนี้อยู่ชั้น
                    ม.5 โรงเรียนกำเนิดวิทย์ มีความสนใจในด้านคอมพิวเตอร์ มีคว่ามถนัดในด้าน Problem solving และ algorithm
                    เคยเขียนภาษา C, C++, Python, HTML, CSS, PHP, Java, JavaScript
                    ตอนนี้กำลังศึกษาในส่วน Machine learning เป็นหลัก ได้รับเลือกเป็นสำรองผู้แทนศูนย์ สอวน. คอมพิวเตอร์
                    ศูนย์โรงเรียนมหิดลวิทยานุสรณ์ ปีการศึกษา 2566
                    งานอดิเรกที่ผมชอบ คือการเล่นบาสเก็ตบอลและเล่นเกมออนไลน์ครับ"
                    <br><br>IG: adulkjt<br>
                    Email: adulkajit@gmail.com<br>
                    Tel: 0929989812
                </p>
            </div>
        </div>
        <div class="column">
            <div class="member">
                <img src="images/138281.jpg" alt="Image of First member" class="round-border">
                <h2>ธนกฤต ดำดวน</h2>
                <p class="about-text">
                    <span class="tab-space"></span>"ผม ธนกฤต ดำดวน สามารถเรียกผมว่าเตมส์ ก็ได้ครับ
                    ผมเป็นนักเรียน ม.5 โรงเรียนกำเนิดวิทย์ มีความสนใจทางด้านคอมพิวเตอร์ และฟิสิกส์
                    รับผิดชอบงานด้าน machine learning model api server และหน้า Userinterface
                    เคยได้ผ่านการอบรมค่าย สอวน.คอมพิวเตอร์ ค่าย 2 ศูนย์สามเสน
                    เป็นผู้แทนศูนย์โรงเรียนมหิดลวิทยานุสรณ์วิชาฟิสิกส์เข้าร่วมการแข่งขันฟิสิกส์โอลิมปิกระดับชาติได้รับรางวัลเหรียญเงินคับ
                    นอกจากทางด้านวิชาการแล้วผมยังมีความสนใจทางด้านการถ่ายภาพอีกด้วยครับ"
                    <br><br>IG: dd_ttnk<br>
                    Email: Thanakrit.dam64@gmail.com<br>
                    Tel: 0843292183
                </p>
            </div>
        </div>
        <div class="column">
            <div class="member">
                <img src="images/Bampic.jpg" alt="Image of First member" class="round-border">
                <h2>ภัคธดา พิธาวราธร</h2>
                <p class="about-text">
                    <span class="tab-space"></span>"สวัสดีค่า หนู ภัคธดา พิธาวราธร หรือ แบมแบมค่ะ
                    ตอนนี้กำลังศึกษาอยู่ชั้นมัธยมศึกษาปีที่ 5
                    โรงเรียนกำเนิดวิทย์ ในด้านวิชาการมีความชื่นชอบและสนใจในคอมพิวเตอร์และ
                    เพราะรู้สึกว่าคอมพิวเตอร์มีอะไรให้เรียนรู้เยอะมาก ๆ
                    และมีประสบการณ์อบรมค่าย สอวน.คอมพิวเตอร์ ค่าย 2 ศูนย์โรงเรียนมหิดลวิทยานุสรณ์ นอกจากด้านวิชาการ
                    หนูยังมีความชื่นชอบในการออกแบบศิลปะอีกด้วยค่ะ"
                    <br><br>IG: _bbxm_phx<br>
                    Email: Phakthada.bb@gmail.com<br>
                    Tel: 0874419145
                </p>
            </div>
        </div>


    </section>
    <section class="banner">
        <h1>Advisor</h1>
    </section>
    <section class="flex-hori">
        <div class="column-white" id="advisor"><img src="images\31713369_2148183592122073_4653734886421561344_n.jpg"
                alt="Advisor image" class="round-border">
        </div>
        <div class="column" id="advisor-text">
            <h2 id="advisor-name">ดร. คเณศ สุเมธพิพัธน์</h2>
            <p class="about-text" id="advisor-dess">
                <span class="tab-space"></span>สวัสดีครับ ผมชื่อนายคเณศ สุเมธพิพัธน์ เป็นครูคณิตศาสตร์
                โรงเรียนกำเนิดวิทย์ มีความสนใจทางด้าน
                Interdisciplinary Applied Mathematics
                โดยมีด้านที่เคยศึกษาหรือทำวิจัยคือ
                the stability of nanomaterials in a system, combinatorial game theory, game theory
                และในปัจจุบัน มีความสนใจทางด้าน
                optimization
                และ
                machine learning
                โดยเฉพาะ
                reinforcement learning
                ทั้งในเชิง
                academic research
                และ
                industrial research
                งานอดิเรกคือเล่น
                Dota2
                และ
                review

                textbook
                คณิตศาสตร์ที่ออกมาล่าสุด
            </p>
        </div>
    </section>
    <section id="feedback">
        <h1 class='Topic'>Give us the feedback</h1>
        <div class='input-form'>
            <form id="feedback-form" method="post" action="process_feedback.php">
                <label for="name">Name (optional):</label>
                <input type="text" id="name" name="name">

                <label for="email">Email (optional):</label>
                <input type="email" id="email" name="email">

                <label for="rating">Rating:</label>
                <input type="range" id="rating" name="rating" min="1" max="5" value="<?php echo $rating_value; ?>">
                <span id="rating-value"><?php echo $rating_value; ?></span>

                <label for="comments">Comments:</label>
                <textarea id="comments" name="comments"></textarea>

                <button type="submit" id="feedback-button">Submit</button>
            </form>
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

    <script>
        const rating = document.getElementById('rating');
        const ratingValue = document.getElementById('rating-value');

        rating.addEventListener('input', function() {
            ratingValue.textContent = rating.value;
        });
        document.getElementById('rating').addEventListener('input', function() {
            document.getElementById('rating-value').innerText = this.value;
        });

        document.getElementById('feedback-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = {
        name: document.getElementById('name').value || '',
        email: document.getElementById('email').value || '',
        rating: document.getElementById('rating').value,
        comments: document.getElementById('comments').value
    };

    fetch('https://tameszaza.pythonanywhere.com/process_feedback', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('There was an error submitting your feedback.');
    });
});
    </script>
</body>

</html>
