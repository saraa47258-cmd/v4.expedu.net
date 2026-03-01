// Course Data for Dynamic Content
const courseData = {
    'programming-basics': {
        id: 'programming-basics',
        title: 'تعلم البرمجة من الصفر إلى الاحتراف',
        instructor: 'د. أحمد المعمري',
        instructorTitle: 'خبير البرمجة والتطوير',
        rating: 4.9,
        ratingCount: 1234,
        studentsCount: 2450,
        duration: '40 ساعة',
        price: {
            current: 299,
            original: 599,
            currency: 'ر.ع'
        },
        badge: 'جديد',
        category: 'البرمجة',
        description: 'دورة شاملة لتعلم البرمجة من الأساسيات إلى المستوى المتقدم، تغطي جميع المفاهيم المهمة في عالم البرمجة والتطوير.',
        icon: 'fas fa-code',
        thumbnail: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        sections: [
            {
                title: 'مقدمة في البرمجة',
                lectures: 8,
                duration: '3 ساعات 20 دقيقة'
            },
            {
                title: 'أساسيات البرمجة',
                lectures: 12,
                duration: '5 ساعات 45 دقيقة'
            },
            {
                title: 'البرمجة المتقدمة',
                lectures: 15,
                duration: '7 ساعات 30 دقيقة'
            },
            {
                title: 'مشاريع عملية',
                lectures: 10,
                duration: '6 ساعات 15 دقيقة'
            }
        ],
        whatYouWillLearn: [
            'أساسيات البرمجة والمفاهيم الأساسية',
            'كتابة الكود بشكل صحيح ومنظم',
            'حل المشاكل البرمجية المعقدة',
            'تطوير التطبيقات والمواقع',
            'العمل مع قواعد البيانات',
            'استخدام أدوات التطوير الحديثة'
        ],
        requirements: [
            'لا توجد متطلبات مسبقة',
            'جهاز كمبيوتر أو لابتوب',
            'اتصال بالإنترنت',
            'الرغبة في التعلم والتطوير'
        ]
    },
    
    'graphic-design': {
        id: 'graphic-design',
        title: 'التصميم الجرافيكي والإبداع الرقمي',
        instructor: 'أ. فاطمة الزهراء',
        instructorTitle: 'مصممة جرافيكية محترفة',
        rating: 4.8,
        ratingCount: 987,
        studentsCount: 1890,
        duration: '32 ساعة',
        price: {
            current: 249,
            original: 499,
            currency: 'ر.ع'
        },
        badge: 'الأكثر شعبية',
        category: 'التصميم',
        description: 'تعلم فن التصميم الجرافيكي والإبداع الرقمي من خلال أدوات وتقنيات حديثة، مع التركيز على الجانب العملي والتطبيقي.',
        icon: 'fas fa-palette',
        thumbnail: 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
        sections: [
            {
                title: 'مقدمة في التصميم الجرافيكي',
                lectures: 6,
                duration: '2 ساعات 30 دقيقة'
            },
            {
                title: 'أدوات التصميم',
                lectures: 10,
                duration: '4 ساعات 15 دقيقة'
            },
            {
                title: 'مبادئ التصميم',
                lectures: 8,
                duration: '3 ساعات 45 دقيقة'
            },
            {
                title: 'مشاريع عملية',
                lectures: 12,
                duration: '5 ساعات 20 دقيقة'
            }
        ],
        whatYouWillLearn: [
            'مبادئ التصميم الجرافيكي الأساسية',
            'استخدام برامج التصميم الاحترافية',
            'إنشاء الشعارات والهويات البصرية',
            'تصميم المطبوعات والمواد التسويقية',
            'التصميم الرقمي والويب',
            'تطوير الحس الفني والإبداعي'
        ],
        requirements: [
            'لا توجد متطلبات مسبقة',
            'جهاز كمبيوتر مع برامج التصميم',
            'اتصال بالإنترنت',
            'الرغبة في الإبداع والتطوير'
        ]
    },
    
    'business-management': {
        id: 'business-management',
        title: 'إدارة الأعمال والقيادة الاستراتيجية',
        instructor: 'د. محمد الشنفري',
        instructorTitle: 'خبير إدارة الأعمال والقيادة',
        rating: 4.9,
        ratingCount: 756,
        studentsCount: 1234,
        duration: '48 ساعة',
        price: {
            current: 399,
            original: 799,
            currency: 'ر.ع'
        },
        badge: 'محدود',
        category: 'الأعمال',
        description: 'دورة متخصصة في إدارة الأعمال والقيادة الاستراتيجية، تغطي جميع جوانب الإدارة الحديثة والقيادة الفعالة.',
        icon: 'fas fa-chart-line',
        thumbnail: 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
        sections: [
            {
                title: 'مقدمة في إدارة الأعمال',
                lectures: 10,
                duration: '4 ساعات 15 دقيقة'
            },
            {
                title: 'القيادة الاستراتيجية',
                lectures: 12,
                duration: '5 ساعات 30 دقيقة'
            },
            {
                title: 'إدارة الفرق',
                lectures: 8,
                duration: '3 ساعات 45 دقيقة'
            },
            {
                title: 'التخطيط الاستراتيجي',
                lectures: 15,
                duration: '7 ساعات 20 دقيقة'
            }
        ],
        whatYouWillLearn: [
            'مبادئ إدارة الأعمال الحديثة',
            'مهارات القيادة الاستراتيجية',
            'إدارة الفرق والمشاريع',
            'التخطيط الاستراتيجي والتطوير',
            'اتخاذ القرارات الإدارية',
            'قياس الأداء والتقييم'
        ],
        requirements: [
            'خبرة عمل أساسية مفضلة',
            'جهاز كمبيوتر أو لابتوب',
            'اتصال بالإنترنت',
            'الرغبة في التطوير المهني'
        ]
    },
    
    'english-professional': {
        id: 'english-professional',
        title: 'تعلم اللغة الإنجليزية للمحترفين',
        instructor: 'أ. سارة البلوشية',
        instructorTitle: 'خبيرة اللغة الإنجليزية',
        rating: 4.7,
        ratingCount: 1456,
        studentsCount: 3210,
        duration: '36 ساعة',
        price: {
            current: 199,
            original: 399,
            currency: 'ر.ع'
        },
        badge: 'مميز',
        category: 'اللغات',
        description: 'دورة متخصصة لتعلم اللغة الإنجليزية للمحترفين، تركز على المهارات العملية والتواصل المهني.',
        icon: 'fas fa-language',
        thumbnail: 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
        sections: [
            {
                title: 'أساسيات اللغة الإنجليزية',
                lectures: 8,
                duration: '3 ساعات 15 دقيقة'
            },
            {
                title: 'المفردات المهنية',
                lectures: 10,
                duration: '4 ساعات 30 دقيقة'
            },
            {
                title: 'التواصل المهني',
                lectures: 12,
                duration: '5 ساعات 45 دقيقة'
            },
            {
                title: 'الكتابة المهنية',
                lectures: 8,
                duration: '3 ساعات 20 دقيقة'
            }
        ],
        whatYouWillLearn: [
            'تحسين مهارات التحدث والاستماع',
            'تعلم المفردات المهنية المتخصصة',
            'التواصل الفعال في بيئة العمل',
            'كتابة التقارير والرسائل المهنية',
            'إجراء العروض التقديمية',
            'فهم الثقافة المهنية الإنجليزية'
        ],
        requirements: [
            'مستوى أساسي في اللغة الإنجليزية',
            'جهاز كمبيوتر أو لابتوب',
            'اتصال بالإنترنت',
            'الرغبة في التطوير المهني'
        ]
    }
};

// Function to get course data by ID
function getCourseData(courseId) {
    return courseData[courseId] || courseData['programming-basics']; // Default fallback
}

// Function to update course detail page
function updateCourseDetailPage() {
    const urlParams = new URLSearchParams(window.location.search);
    const courseId = urlParams.get('course') || 'programming-basics';
    const course = getCourseData(courseId);
    
    // Update page title
    document.title = `${course.title} - مواهب خبرة`;
    
    // Update course title
    const courseTitleElement = document.querySelector('.course-title');
    if (courseTitleElement) {
        courseTitleElement.textContent = course.title;
    }
    
    // Update course subtitle (instructor info)
    const courseSubtitleElement = document.querySelector('.course-subtitle');
    if (courseSubtitleElement) {
        courseSubtitleElement.textContent = `${course.description} | ${course.instructor}`;
    }
    
    // Update badge
    const badgeElement = document.querySelector('.badge-custom');
    if (badgeElement) {
        badgeElement.textContent = course.badge;
    }
    
    // Update rating
    const ratingTextElement = document.querySelector('.rating-text');
    if (ratingTextElement) {
        ratingTextElement.textContent = `${course.rating} (${course.ratingCount.toLocaleString()} من التقييمات)`;
    }
    
    // Update students count
    const studentsElement = document.querySelector('.rating-text:nth-child(3)');
    if (studentsElement) {
        studentsElement.textContent = `${course.studentsCount.toLocaleString()} من الطلاب`;
    }
    
    // Update course meta (instructor)
    const metaItemElement = document.querySelector('.meta-item');
    if (metaItemElement) {
        metaItemElement.textContent = `تم الإنشاء بواسطة ${course.instructor}`;
    }
    
    // Update course description
    const descriptionElement = document.querySelector('.course-description');
    if (descriptionElement) {
        descriptionElement.textContent = course.description;
    }
    
    // Update course icon in video section
    const iconElement = document.querySelector('.course-icon');
    if (iconElement) {
        iconElement.className = `fas ${course.icon}`;
    }
    
    // Update thumbnail background in video section
    const thumbnailElement = document.querySelector('.course-thumbnail');
    if (thumbnailElement) {
        thumbnailElement.style.background = course.thumbnail;
    }
    
    // Update what you'll learn section
    const learnListElement = document.querySelector('.learn-list');
    if (learnListElement) {
        learnListElement.innerHTML = '';
        course.whatYouWillLearn.forEach(item => {
            const li = document.createElement('li');
            li.innerHTML = `<i class="fas fa-check text-success me-2"></i>${item}`;
            learnListElement.appendChild(li);
        });
    }
    
    // Update course content sections
    const contentListElement = document.querySelector('.course-content-list');
    if (contentListElement) {
        contentListElement.innerHTML = '';
        course.sections.forEach((section, index) => {
            const sectionHtml = `
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading${index}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${index}">
                            <i class="fas fa-play-circle me-2"></i>
                            ${section.title}
                            <span class="ms-auto text-muted">${section.lectures} محاضرة • ${section.duration}</span>
                        </button>
                    </h2>
                    <div id="collapse${index}" class="accordion-collapse collapse" data-bs-parent="#courseContent">
                        <div class="accordion-body">
                            <p>محاضرات تفصيلية تغطي جميع جوانب ${section.title.toLowerCase()}</p>
                        </div>
                    </div>
                </div>
            `;
            contentListElement.innerHTML += sectionHtml;
        });
    }
    
    // Update sidebar pricing
    const currentPriceElement = document.querySelector('.current-price');
    if (currentPriceElement) {
        currentPriceElement.textContent = `${course.price.current} ${course.price.currency}`;
    }
    
    const originalPriceElement = document.querySelector('.original-price');
    if (originalPriceElement) {
        originalPriceElement.textContent = `${course.price.original} ${course.price.currency}`;
    }
    
    // Update sidebar course info
    const sidebarDurationElement = document.querySelector('.course-duration');
    if (sidebarDurationElement) {
        sidebarDurationElement.textContent = course.duration;
    }
    
    const sidebarStudentsElement = document.querySelector('.students-count');
    if (sidebarStudentsElement) {
        sidebarStudentsElement.textContent = `${course.studentsCount.toLocaleString()} طالب`;
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    updateCourseDetailPage();
});
