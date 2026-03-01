<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="الأسئلة الشائعة - بوابة خبرة ">
    <title>الأسئلة الشائعة - بوابة خبرة </title>
    
    <!-- Bootstrap RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
    <!-- Navbar & Footer CSS -->
    <link href="assets/css/navbar.css" rel="stylesheet">
    <link href="assets/css/footer.css" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        body {
            background: #f8f9fa;
            padding-top: 80px;
        }
        
        /* Hero */
        .hero-section {
            background: linear-gradient(135deg, #066755 0%, #044a3d 100%);
            color: white;
            padding: 80px 0;
            margin-bottom: 60px;
            border-radius: 0 0 50px 50px;
        }
        
        .hero-title {
            font-size: 3rem;
            font-weight: 900;
        }
        
        /* FAQ Item */
        .accordion-item {
            border: none;
            margin-bottom: 20px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        
        .accordion-button {
            background: white;
            color: #066755;
            font-weight: 700;
            font-size: 1.1rem;
            padding: 20px 25px;
            border: none;
        }
        
        .accordion-button:not(.collapsed) {
            background: linear-gradient(135deg, #066755, #00d4ff);
            color: white;
            box-shadow: none;
        }
        
        .accordion-button:focus {
            box-shadow: 0 0 0 4px rgba(6, 103, 85, 0.1);
        }
        
        .accordion-body {
            padding: 25px;
            line-height: 1.8;
            font-size: 1.05rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }
            
            .hero-section {
                padding: 50px 0;
                margin-bottom: 40px;
                border-radius: 0 0 30px 30px;
            }
            
            .accordion-button {
                font-size: 1rem;
                padding: 16px 20px;
            }
            
            .accordion-body {
                padding: 20px;
                font-size: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .hero-title {
                font-size: 1.8rem;
            }
            
            .hero-section {
                padding: 40px 0;
                margin-bottom: 30px;
            }
            
            .accordion-button {
                font-size: 0.95rem;
                padding: 14px 16px;
            }
        }
        
        @media (max-width: 374.98px) {
            .hero-title {
                font-size: 1.5rem;
            }
            
            .accordion-button {
                font-size: 0.9rem;
                padding: 12px 14px;
            }
            
            .accordion-body {
                padding: 14px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body class="has-premium-navbar">
    <!-- Navbar -->
    <?php $currentPage = 'faq'; include 'includes/navbar.php'; ?>
    
    <!-- Hero -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="hero-title">الأسئلة الشائعة</h1>
            <p class="lead" style="font-size: 1.3rem; opacity: 0.95;">
                إجابات شاملة على أكثر الأسئلة شيوعاً
            </p>
        </div>
    </section>
    
    <!-- FAQ Content -->
    <section class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="accordion" id="faqAccordion">
                    <!-- السؤال 1 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                <i class="fas fa-question-circle me-2"></i>
                                ما هي بوابة خبرة؟
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                بوابة خبرة هي منصة رقمية عُمانية مرخصة، تقدم حلولاً تعليمية وخدمات تنفيذية وتخصصية متكاملة. نعمل منذ عام 2020م على تمكين الأفراد والمؤسسات من خلال برامج تدريبية وإرشادية، وتحويل المعرفة إلى نتائج ملموسة تخدم تطلعاتكم وتدعم نمو مؤسساتكم.
                            </div>
                        </div>
                    </div>
                    
                    <!-- السؤال 2 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                <i class="fas fa-question-circle me-2"></i>
                                ما الفرق بين الدورات والخدمات؟
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <strong>الدورات:</strong> برامج  مهيكلة بمحتوى محدد ومدة زمنية معينة، مثل الدورات التخصصية، الورش التدريبية، والبرامج الأكاديمية.<br><br>
                                <strong>الخدمات:</strong> حلول مخصصة تُصمم حسب احتياجك، مثل التصميم والتسويق، المواقع الإلكترونية والتطبيقات، والتصميم الداخلي والخارجي.
                            </div>
                        </div>
                    </div>
                    
                    <!-- السؤال 3 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                <i class="fas fa-question-circle me-2"></i>
                                كيف أنضم كخبير أو مشرف؟
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                يمكنك الانضمام بكل سهولة عبر صفحة <a href="join.php" class="fw-bold" style="color: #066755;">"انضم إلينا"</a>:
                                <ol class="mt-3">
                                    <li>اختر "انضم كخبير" أو "شارك كمشرف"</li>
                                    <li>املأ النموذج بمعلوماتك وخبراتك</li>
                                    <li>أرفق سيرتك الذاتية (اختياري للخبراء)</li>
                                    <li>اضغط "إرسال"</li>
                                </ol>
                                سنتواصل معك خلال <strong>48 ساعة</strong> لمناقشة التفاصيل.
                            </div>
                        </div>
                    </div>
                    
                    <!-- السؤال 4 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                <i class="fas fa-question-circle me-2"></i>
                                ما آلية الدفع؟
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                نوفر عدة طرق دفع مرنة:
                                <ul class="mt-3">
                                    <li><strong>التحويل البنكي:</strong> عبر حسابنا البنكي المعتمد</li>
                                    <li><strong>الدفع الإلكتروني:</strong> بطاقات الائتمان والخصم</li>
                                    <li><strong>الدفع عند الاستلام:</strong> لبعض الخدمات</li>
                                    <li><strong>خطط التقسيط:</strong> للدورات الطويلة والبرامج المتقدمة</li>
                                </ul>
                                للمزيد من التفاصيل، <a href="index.php#contact" class="fw-bold" style="color: #066755;">تواصل معنا</a>.
                            </div>
                        </div>
                    </div>
                    
                    <!-- أسئلة إضافية -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                <i class="fas fa-question-circle me-2"></i>
                                هل تقدمون شهادات معتمدة؟
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                نعم، نقدم <strong>شهادات حضور معتمدة</strong> لجميع الدورات والبرامج التدريبية. الشهادات تحمل ختم بوابة خبرة الرسمي ويمكن التحقق من صحتها إلكترونياً.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                                <i class="fas fa-question-circle me-2"></i>
                                كم مدة الدورات؟
                            </button>
                        </h2>
                        <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                تختلف مدة الدورات حسب المحتوى:
                                <ul class="mt-3">
                                    <li><strong>الورش القصيرة:</strong> 3-6 ساعات (يوم واحد)</li>
                                    <li><strong>الدورات المتوسطة:</strong> 10-20 ساعة (أسبوع - أسبوعين)</li>
                                    <li><strong>البرامج المتقدمة:</strong> 30-60 ساعة (شهر - شهرين)</li>
                                    <li><strong>الخدمات:</strong> حسب الاتفاق والمتطلبات</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq7">
                                <i class="fas fa-question-circle me-2"></i>
                                هل التدريب أونلاين أم حضوري؟
                            </button>
                        </h2>
                        <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                نقدم <strong>كلا الخيارين</strong>:
                                <ul class="mt-3">
                                    <li><strong>التدريب أونلاين:</strong> عبر منصات تفاعلية (Zoom, Teams)</li>
                                    <li><strong>التدريب الحضوري:</strong> في مقر بوابة خبرة أو موقع العميل</li>
                                    <li><strong>التدريب المدمج (Hybrid):</strong> مزيج بين الأونلاين والحضوري</li>
                                </ul>
                                يمكنك اختيار الطريقة الأنسب لك عند التسجيل.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq8">
                                <i class="fas fa-question-circle me-2"></i>
                                كيف أتواصل مع خدمة العملاء؟
                            </button>
                        </h2>
                        <div id="faq8" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                يمكنك التواصل معنا عبر:
                                <ul class="mt-3">
                                    <li><strong>الهاتف:</strong> <a href="tel:+96892332749" class="fw-bold" style="color: #066755;">+968 92332749</a></li>
                                    <li><strong>البريد:</strong> <a href="mailto:info.expertplatform@gmail.com" class="fw-bold" style="color: #066755;">info.expertplatform@gmail.com</a></li>
                                    <li><strong>WhatsApp:</strong> متاح 24/7</li>
                                    <li><strong>نموذج التواصل:</strong> <a href="index.php#contact" class="fw-bold" style="color: #066755;">في الصفحة الرئيسية</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA -->
    <section class="container mb-5">
        <div class="text-center p-5 rounded-4" style="background: linear-gradient(135deg, rgba(6, 103, 85, 0.1), rgba(0, 212, 255, 0.1)); border: 3px solid #066755;">
            <h2 class="fw-bold mb-3" style="color: #066755;">لم تجد إجابة لسؤالك؟</h2>
            <p class="lead mb-4">تواصل معنا مباشرة وسنساعدك</p>
            <a href="index.php#contact" class="btn btn-lg" style="background: linear-gradient(135deg, #066755, #00d4ff); color: white; padding: 15px 40px; border-radius: 15px; font-weight: 700;">
                <i class="fas fa-envelope me-2"></i>
                تواصل معنا
            </a>
        </div>
    </section>
    
    <!-- Premium Footer -->
    <footer class="premium-footer">
        <div class="footer-wave">
            <svg viewBox="0 0 1440 120" preserveAspectRatio="none">
                <path fill="#066755" d="M0,60 C360,120 1080,0 1440,60 L1440,120 L0,120 Z"></path>
            </svg>
        </div>
        <div class="footer-main">
            <div class="container">
                <div class="row g-5">
                    <div class="col-lg-4">
                        <div class="footer-brand">
                            <div class="brand-logo">
                                <img src="assets/icons/logo.png" alt="بوابة خبرة">
                                <div class="brand-text"><h4>بوابة خبرة</h4></div>
                            </div>
                            <p class="brand-desc">منصتك للتعلّم والتطوير وصناعة المستقبل. نُمكّن الأفراد والمؤسسات منذ 2020م عبر دورات تخصصية وخدمات متكاملة.</p>
                            <div class="contact-cards">
                                <a href="tel:+96892332749" class="contact-card">
                                    <div class="contact-icon phone"><i class="fas fa-phone-alt"></i></div>
                                    <div class="contact-details"><span class="label">اتصل بنا</span><span class="value" dir="ltr">+968 92332749</span></div>
                                </a>
                                <a href="mailto:info.expertplatform@gmail.com" class="contact-card">
                                    <div class="contact-icon email"><i class="fas fa-envelope"></i></div>
                                    <div class="contact-details"><span class="label">البريد الإلكتروني</span><span class="value">info.expertplatform@gmail.com</span></div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="row g-4">
                            <div class="col-6">
                                <div class="footer-links">
                                    <h5 class="links-title"><i class="fas fa-link"></i> روابط سريعة</h5>
                                    <ul>
                                        <li><a href="index.php"><i class="fas fa-chevron-left"></i> الرئيسية</a></li>
                                        <li><a href="about.php"><i class="fas fa-chevron-left"></i> من نحن</a></li>
                                        <li><a href="services.php"><i class="fas fa-chevron-left"></i> خدماتنا</a></li>
                                        <li><a href="courses.php"><i class="fas fa-chevron-left"></i> الكورسات</a></li>
                                        <li><a href="team.php"><i class="fas fa-chevron-left"></i> فريقنا</a></li>
                                        <li><a href="join.php"><i class="fas fa-chevron-left"></i> انضم إلينا</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="footer-links">
                                    <h5 class="links-title"><i class="fas fa-cogs"></i> خدماتنا</h5>
                                    <ul>
                                        <li><a href="services.php"><i class="fas fa-chevron-left"></i> التصميم والتسويق</a></li>
                                        <li><a href="services.php"><i class="fas fa-chevron-left"></i> المواقع الإلكترونية والتطبيقات</a></li>
                                        <li><a href="services.php"><i class="fas fa-chevron-left"></i> التصميم الداخلي والخارجي</a></li>
                                        <li><a href="services.php"><i class="fas fa-chevron-left"></i> الدروس الخصوصية</a></li>
                                        <li><a href="services.php"><i class="fas fa-chevron-left"></i> وغيرها</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="footer-cta">
                            <h5 class="cta-title"><i class="fas fa-comments"></i> تواصل معنا</h5>
                            <p class="cta-desc">نسعد بالإجابة على استفساراتك</p>
                            <a href="https://wa.me/96892332749" class="whatsapp-btn" target="_blank" rel="noopener noreferrer"><i class="fab fa-whatsapp"></i><span>تواصل عبر واتساب</span></a>
                            <div class="social-icons">
                                <span class="social-label">تابعنا على</span>
                                <div class="icons-row">
                                    <a href="https://instagram.com/exp_edu_" target="_blank" rel="noopener noreferrer" class="social-icon instagram" title="انستقرام"><i class="fab fa-instagram"></i></a>
                                    <a href="https://linkedin.com/company/exp-edu" target="_blank" rel="noopener noreferrer" class="social-icon linkedin" title="لينكدإن"><i class="fab fa-linkedin-in"></i></a>
                                    <a href="https://youtube.com/@exp_edu" target="_blank" rel="noopener noreferrer" class="social-icon youtube" title="يوتيوب"><i class="fab fa-youtube"></i></a>
                                    <a href="https://x.com/exp_edu_" target="_blank" rel="noopener noreferrer" class="social-icon twitter x-social" title="إكس" aria-label="إكس"><span class="x-social-mark">X</span></a>
                                </div>
                            </div>
                            <div class="newsletter-mini">
                                <span>اشترك في النشرة البريدية</span>
                                <div class="newsletter-form">
                                    <input type="email" placeholder="بريدك الإلكتروني" aria-label="البريد الإلكتروني">
                                    <button type="button" aria-label="اشتراك"><i class="fas fa-paper-plane"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="bottom-content">
                    <div class="copyright"><span>© <?= date('Y') ?> بوابة خبرة</span><span class="separator">|</span><span>جميع الحقوق محفوظة</span></div>
                    <div class="bottom-links"><a href="#">سياسة الخصوصية</a><a href="#">الشروط والأحكام</a><a href="index.php#contact">تواصل معنا</a></div>
                    <div class="made-with"><span>صُنع بـ</span><i class="fas fa-heart"></i><span>في عُمان</span></div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
