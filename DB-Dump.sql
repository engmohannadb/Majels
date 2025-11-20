-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2025 at 05:49 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `designerswall`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrators`
--

CREATE TABLE `administrators` (
  `ID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Password` varchar(32) NOT NULL,
  `Register_Date` datetime NOT NULL DEFAULT current_timestamp(),
  `Last_Login` datetime DEFAULT NULL,
  `User_Managment` tinyint(1) NOT NULL DEFAULT 0,
  `Picture_Managment` tinyint(1) NOT NULL DEFAULT 0,
  `Categories_Managment` tinyint(1) NOT NULL DEFAULT 0,
  `Super_Managment` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `ID` int(11) NOT NULL,
  `Category_Name` varchar(50) NOT NULL,
  `Description` varchar(200) DEFAULT NULL,
  `Creator` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `sent_at` datetime DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`id`, `sender_id`, `receiver_id`, `message`, `sent_at`, `is_read`) VALUES
(1, 1, 2, 'مرحبًا، هل تم إصلاح المصعد اليوم؟', '2025-11-05 07:58:31', 1),
(2, 2, 1, 'نعم، تمت الصيانة صباح اليوم.', '2025-11-05 07:58:31', 1),
(3, 3, 5, 'يرجى إغلاق الإنارة في المواقف بعد الاستخدام.', '2025-11-05 07:58:31', 0),
(4, 4, 6, 'هل تم استلام طلب الصيانة للوحدة 4؟', '2025-11-05 07:58:31', 1),
(5, 5, 3, 'سيتم تنفيذ الطلب غدًا بإذن الله.', '2025-11-05 07:58:31', 1),
(6, 6, 4, 'تمت الموافقة على المقترح الجديد.', '2025-11-05 07:58:31', 0),
(7, 7, 8, 'هل يوجد اجتماع غدًا؟', '2025-11-05 07:58:31', 1),
(8, 8, 7, 'نعم في الساعة الثامنة مساءً.', '2025-11-05 07:58:31', 1),
(9, 9, 10, 'يرجى تنظيف السطح بعد الصيانة.', '2025-11-05 07:58:31', 0),
(10, 10, 9, 'تم الإبلاغ للفريق، سيتم ذلك خلال اليوم.', '2025-11-05 07:58:31', 1);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` datetime NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `unit_id`, `title`, `description`, `event_date`, `created_by`, `created_at`) VALUES
(21, 11, 'اجتماع الملاك الشهري', 'مناقشة أمور الصيانة والخدمات العامة', '2025-01-23 19:00:00', 1, '2025-11-05 08:00:58'),
(22, 12, 'ورشة عمل الأمن والسلامة', 'توعية السكان بإجراءات السلامة', '2025-02-01 17:30:00', 2, '2025-11-05 08:00:58'),
(23, 13, 'نهاية التصويت على مقترح الحضانة', 'إعلان نتائج التصويت', '2025-01-25 14:00:00', 3, '2025-11-05 08:00:58'),
(24, 14, 'تحديث نظام الدخول الذكي', 'تركيب أجهزة دخول بالبصمة', '2025-02-05 09:00:00', 4, '2025-11-05 08:00:58'),
(25, 15, 'اجتماع سنوي للملاك', 'مناقشة الميزانية السنوية للمبنى', '2025-03-10 18:00:00', 5, '2025-11-05 08:00:58'),
(26, 16, 'فعالية بيئية', 'تشجير محيط الوحدة بالتعاون مع بلدية الخبر', '2025-04-15 10:00:00', 6, '2025-11-05 08:00:58'),
(27, 17, 'يوم العائلة', 'تنظيم يوم مفتوح للعائلات المقيمة', '2025-02-20 16:00:00', 7, '2025-11-05 08:00:58'),
(28, 18, 'صيانة المصعد الرئيسي', 'جدول صيانة لمدة يومين', '2025-01-18 08:00:00', 8, '2025-11-05 08:00:58'),
(29, 19, 'نهاية أعمال تطوير الحديقة', 'افتتاح الحديقة المشتركة', '2025-03-01 09:30:00', 9, '2025-11-05 08:00:58'),
(30, 20, 'إغلاق الطريق المؤقت', 'بسبب أعمال البلدية أمام المبنى', '2025-01-29 06:00:00', 10, '2025-11-05 08:00:58'),
(31, 24, 'مارثون الرياض', 'vvvvvvvvvv', '2025-11-01 11:11:00', 1, '2025-11-17 17:29:56');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `ID` int(11) NOT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `Sender` int(11) NOT NULL,
  `Receiver` int(11) DEFAULT NULL,
  `Message` varchar(500) NOT NULL,
  `Sending_Time` datetime NOT NULL DEFAULT current_timestamp(),
  `Read_Y_N` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`ID`, `unit_id`, `Sender`, `Receiver`, `Message`, `Sending_Time`, `Read_Y_N`) VALUES
(1, 11, 1, 2, 'يرجى الانتباه، هناك أعمال صيانة جارية في الممر الشرقي.', '2025-11-01 09:00:00', 0),
(2, 12, 2, 1, 'خطر الانزلاق: الأرضية مبللة بالقرب من المدخل الرئيسي.', '2025-11-01 10:15:00', 0),
(3, 13, 3, 1, 'تحذير: الشاحنات لا يُسمح لها بالمرور في هذا الطريق.', '2025-11-01 11:30:00', 1),
(4, 15, 4, 2, 'يرجى عدم إلقاء المخلفات خارج الأماكن المخصصة لها.', '2025-11-01 12:00:00', 0),
(5, 14, 5, 3, 'أرجو عدم التدخين بالقرب من المداخل/النوافذ حفاظاً على بيئة صحية.', '2025-11-01 13:10:00', 0),
(6, 17, 6, 1, 'تذكير: سيتم قطع المياه مؤقتاً يوم الأربعاء لأعمال الفحص.', '2025-11-01 14:00:00', 1),
(7, 11, 2, 3, 'انتبه: الباب يعمل بشكل آلي وقد يُغلق فجأة.', '2025-11-01 15:00:00', 0),
(8, 18, 3, 2, 'يمنع استخدام المصعد في حالة نشوب حريق، يرجى استخدام الدرج.', '2025-11-01 16:00:00', 1),
(9, 12, 1, 2, 'نحتاج لزيادة عدد حاويات القمامة في المنطقة الخلفية.', '2025-11-02 09:00:00', 0),
(10, 19, 2, 3, 'الإضاءة في الدرج (الطابق الخامس) لا تعمل وتحتاج إلى فحص فوري.', '2025-11-02 09:30:00', 1),
(11, 15, 3, 4, 'يرجى تخصيص مكان آمن لوقوف الدراجات الهوائية.', '2025-11-02 10:00:00', 0),
(12, 13, 4, 1, 'مكيف الهواء في غرفة الاجتماعات (رقم 3) يحتاج إلى صيانة.', '2025-11-02 10:45:00', 0),
(13, 11, 5, 2, 'نطالب بضرورة تعيين حارس أمن إضافي في الفترة المسائية.', '2025-11-02 11:30:00', 1),
(14, 16, 6, 3, 'المياه تتسرب من السقف في المطبخ المشترك.', '2025-11-02 12:00:00', 0),
(15, 17, 1, 4, 'يرجى التأكد من إغلاق بوابة موقف السيارات بعد الدخول/الخروج.', '2025-11-02 12:30:00', 0),
(16, 16, 3, 2, 'الطلاء الخارجي للمبنى يحتاج إلى تجديد عاجل.', '2025-11-02 13:00:00', 1),
(17, 19, 2, 1, 'شكراً لالتزامكم بالهدوء بعد الساعة 10 مساءً.', '2025-11-03 09:00:00', 0),
(18, 16, 3, 1, 'نقدر تعاونكم الكبير في الحفاظ على نظافة المكان.', '2025-11-03 09:30:00', 1),
(19, 11, 4, 2, 'نثمن جهودكم في الإبلاغ عن أي مشاكل أو أعطال فور ملاحظتها.', '2025-11-03 10:00:00', 0),
(20, 15, 5, 3, 'كل الشكر والامتنان على تفهمكم للوضع الحالي.', '2025-11-03 10:30:00', 1),
(21, 18, 1, 3, 'تم تحديث نظام الدخول الذكي للمبنى بنجاح.', '2025-11-03 11:00:00', 0),
(22, 17, 2, 4, 'سيتم تنظيف مواقف السيارات يوم الخميس القادم.', '2025-11-03 11:30:00', 1),
(23, 11, 3, 1, 'يرجى مراجعة الإدارة بخصوص الفواتير المتأخرة.', '2025-11-03 12:00:00', 0),
(24, 12, 4, 2, 'تذكير بحضور اجتماع لجنة الملاك غداً الساعة 7 مساءً.', '2025-11-03 12:30:00', 1),
(25, 14, 5, 3, 'يرجى التأكد من إغلاق النوافذ أثناء العواصف.', '2025-11-03 13:00:00', 0),
(26, NULL, 6, 1, 'تمت الموافقة على طلب الصيانة الخاص بك.', '2025-11-03 13:30:00', 1),
(27, NULL, 1, 2, 'تم استلام الطرد الخاص بك في مكتب الأمن.', '2025-11-03 14:00:00', 0),
(28, NULL, 2, 3, 'يرجى التواصل مع الإدارة لتجديد تصريح الموقف.', '2025-11-03 14:30:00', 1),
(29, NULL, 3, 4, 'سيتم تركيب كاميرات مراقبة جديدة الأسبوع القادم.', '2025-11-03 15:00:00', 0),
(30, NULL, 4, 1, 'نرجو من السكان المشاركة في حملة التشجير يوم السبت.', '2025-11-03 15:30:00', 1),
(31, NULL, 5, 2, 'يرجى عدم استخدام المصعد أثناء عملية التنظيف.', '2025-11-03 16:00:00', 0),
(32, NULL, 6, 3, 'تمت معالجة الشكوى المتعلقة بالإنارة في الممر.', '2025-11-03 16:30:00', 1),
(33, NULL, 1, 4, 'يرجى مراجعة مكتب الإدارة لتحديث بيانات الاتصال.', '2025-11-03 17:00:00', 0),
(34, NULL, 2, 1, 'تم إضافة ميزة جديدة في التطبيق لإرسال البلاغات مباشرة.', '2025-11-03 17:30:00', 1),
(35, NULL, 3, 2, 'يرجى الحفاظ على الهدوء في المناطق المشتركة.', '2025-11-03 18:00:00', 0),
(36, NULL, 4, 3, 'سيتم عمل صيانة دورية لأنظمة التكييف الأسبوع القادم.', '2025-11-03 18:30:00', 1),
(37, NULL, 5, 1, 'يرجى إخلاء المواقف الشرقية مؤقتاً لأعمال الرصف.', '2025-11-03 19:00:00', 0),
(38, NULL, 6, 2, 'تم تمديد موعد تسليم الطلبات الإدارية حتى نهاية الأسبوع.', '2025-11-03 19:30:00', 1),
(39, NULL, 1, 3, 'يرجى الحضور إلى الإدارة لتوقيع الاتفاقية الجديدة.', '2025-11-03 20:00:00', 0),
(40, NULL, 2, 4, 'نذكّركم بضرورة تحديث بيانات العضوية سنوياً.', '2025-11-03 20:30:00', 1),
(41, NULL, 3, 1, 'تم إصلاح العطل في بوابة الدخول الرئيسية.', '2025-11-03 21:00:00', 0),
(42, NULL, 4, 2, 'يرجى الالتزام بمواعيد إخراج النفايات المحددة.', '2025-11-03 21:30:00', 1),
(43, NULL, 5, 3, 'يرجى التأكد من إغلاق الأبواب بعد استخدام القاعات.', '2025-11-03 22:00:00', 0),
(44, NULL, 6, 1, 'نقدر مبادرتكم في تنظيف المساحات المشتركة.', '2025-11-03 22:30:00', 1),
(45, NULL, 1, 4, 'يرجى ترك مساحة كافية أمام المداخل لمرور سيارات الطوارئ.', '2025-11-03 23:00:00', 0),
(46, NULL, 2, 3, 'سيتم فحص نظام الحريق المركزي هذا الأسبوع.', '2025-11-03 23:30:00', 1),
(47, NULL, 3, 2, 'يرجى التحقق من عدادات المياه والكهرباء كل شهر.', '2025-11-04 00:00:00', 0),
(48, NULL, 4, 1, 'نذكّركم بموعد الاجتماع الشهري يوم الأحد.', '2025-11-04 00:30:00', 1),
(49, NULL, 5, 2, 'يرجى عدم ركن السيارات خارج الأماكن المخصصة.', '2025-11-04 01:00:00', 0),
(50, NULL, 6, 3, 'شكراً لالتزامكم بإجراءات السلامة.', '2025-11-04 01:30:00', 1),
(51, 12, 1, NULL, 'تجربة 2222', '2025-11-17 18:11:23', 0);

-- --------------------------------------------------------

--
-- Table structure for table `pictures`
--

CREATE TABLE `pictures` (
  `ID` int(11) NOT NULL,
  `File_Name` varchar(50) NOT NULL,
  `Picture_Title` varchar(50) NOT NULL,
  `Category_ID` int(11) NOT NULL,
  `Owner_ID` int(11) NOT NULL,
  `Album_ID` int(11) DEFAULT NULL,
  `Description` varchar(200) DEFAULT NULL,
  `Upload_Date` datetime NOT NULL DEFAULT current_timestamp(),
  `View_Counter` int(11) NOT NULL DEFAULT 0,
  `Design_Program` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `request_title` varchar(255) NOT NULL,
  `request_text` text NOT NULL,
  `request_date` datetime NOT NULL DEFAULT current_timestamp(),
  `last_update` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `status` enum('open','pending','in review','in progress','hold','done','canceled') NOT NULL DEFAULT 'open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `unit_id`, `user_id`, `request_title`, `request_text`, `request_date`, `last_update`, `status`) VALUES
(1, 11, 1, 'تسريب في الحمام', 'يوجد تسريب واضح من مواسير الحمام ويتسبب في تجمع مياه بشكل مستمر.', '2025-11-17 18:26:07', NULL, 'open'),
(2, 12, 1, 'عطل في المكيف', 'المكيف في غرفة النوم لا يعمل ويصدر صوت طنين متكرر عند محاولة تشغيله.', '2025-11-17 18:26:07', NULL, 'pending'),
(3, 13, 1, 'إنارة تالفة', 'الإنارة في مدخل الشقة لا تعمل نهائياً وتحتاج إلى استبدال المصابيح.', '2025-11-17 18:26:07', NULL, 'in progress'),
(4, 11, 1, 'انسداد في المجاري', 'تصريف المطبخ بطيء جداً ويبدو أن هناك انسداد يحتاج إلى تنظيف طارئ.', '2025-11-17 18:26:07', NULL, 'in review'),
(5, 12, 1, 'خزان الماء يحتاج تنظيف', 'لاحظنا وجود رائحة غير طبيعية في الماء ويبدو أن الخزان يحتاج إلى تنظيف وتعقيم.', '2025-11-17 18:26:07', NULL, 'hold'),
(6, 13, 2, 'كسر في باب الشرفة', 'باب الشرفة لا يغلق بإحكام ويوجد خلل في المفصلات يحتاج صيانة.', '2025-11-17 18:26:53', NULL, 'open'),
(7, 11, 3, 'مشكلة في السخان', 'السخان لا يسخن الماء بشكل كافٍ منذ يومين، ويرجى فحصه بشكل عاجل.', '2025-11-17 18:26:53', NULL, 'pending'),
(8, 12, 4, 'انقطاع الكهرباء الجزئي', 'جزء من الشقة لا تصل إليه الكهرباء، قد تكون المشكلة من الأسلاك الداخلية.', '2025-11-17 18:26:53', NULL, 'in progress'),
(9, 13, 2, 'كسر في باب الشرفة', 'باب الشرفة لا يغلق بإحكام ويوجد خلل في المفصلات يحتاج صيانة.', '2025-11-17 18:27:04', NULL, 'open'),
(10, 11, 3, 'مشكلة في السخان', 'السخان لا يسخن الماء بشكل كافٍ منذ يومين، ويرجى فحصه بشكل عاجل.', '2025-11-17 18:27:04', NULL, 'pending'),
(11, 12, 4, 'انقطاع الكهرباء الجزئي', 'جزء من الشقة لا تصل إليه الكهرباء، قد تكون المشكلة من الأسلاك الداخلية.', '2025-11-17 18:27:04', NULL, 'in progress'),
(12, 14, 1, 'test2', 'test555', '2025-11-17 18:34:40', '2025-11-17 18:54:23', 'done'),
(13, 24, 1, 'test2', 'ssssssssssss', '2025-11-18 08:32:29', NULL, 'open');

-- --------------------------------------------------------

--
-- Table structure for table `request_comments`
--

CREATE TABLE `request_comments` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment_text` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request_comments`
--

INSERT INTO `request_comments` (`id`, `request_id`, `user_id`, `comment_text`, `created_at`) VALUES
(1, 1, 1, 'تم استلام الطلب وسيتم معالجته قريباً.', '2025-11-17 18:50:37'),
(2, 1, 2, 'شكراً على الإبلاغ، جاري إرسال فريق الصيانة.', '2025-11-17 18:50:37'),
(3, 2, 3, 'تم جدولة زيارة الفني يوم غد.', '2025-11-17 18:50:37'),
(4, 2, 1, 'يرجى التأكد من وجود الوصول إلى السقف قبل وصول الفني.', '2025-11-17 18:50:37'),
(5, 3, 4, 'تم تغيير الفلتر، يرجى التحقق الآن.', '2025-11-17 18:50:37'),
(6, 3, 5, 'هل لاحظت أي تسريب بعد التنظيف؟', '2025-11-17 18:50:37'),
(7, 4, 2, 'المشكلة قيد المراجعة من قسم الهندسة.', '2025-11-17 18:50:37'),
(8, 4, 3, 'نرجو تحديثنا بأي مستجدات.', '2025-11-17 18:50:37'),
(9, 5, 1, 'تم تعليق الطلب مؤقتاً بسبب نقص المواد.', '2025-11-17 18:50:37'),
(10, 5, 4, 'يرجى الانتظار حتى وصول المواد المطلوبة.', '2025-11-17 18:50:37'),
(11, 6, 5, 'تم الانتهاء من تصليح المكيف.', '2025-11-17 18:50:37'),
(12, 6, 2, 'تم اختبار التشغيل ويعمل بشكل ممتاز.', '2025-11-17 18:50:37'),
(13, 7, 3, 'تم إلغاء الطلب بناءً على طلب المستخدم.', '2025-11-17 18:50:37'),
(14, 7, 1, 'تم تعديل الحالة في النظام.', '2025-11-17 18:50:37'),
(15, 8, 4, 'الفني في الطريق للوحدة الساعة 10 صباحاً.', '2025-11-17 18:50:37'),
(16, 8, 5, 'يرجى تجهيز المنطقة قبل وصول الفني.', '2025-11-17 18:50:37'),
(17, 9, 2, 'تم استبدال المصباح الرئيسي في المدخل.', '2025-11-17 18:50:37'),
(18, 9, 3, 'الإنارة الآن تعمل بشكل كامل.', '2025-11-17 18:50:37'),
(19, 10, 1, 'تم إرسال إشعار لجميع السكان بخصوص انقطاع المياه.', '2025-11-17 18:50:37'),
(20, 10, 4, 'تم حل المشكلة وإعادة تشغيل المضخة.', '2025-11-17 18:50:37'),
(21, 12, 1, 'نآمل الانجاز بشكل عاجل', '2025-11-17 18:54:11');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `complex_type` varchar(100) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `unit_count` int(11) DEFAULT NULL,
  `facilities` text DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `total_members` int(11) DEFAULT 0,
  `total_requests` int(11) DEFAULT 0,
  `pending_requests` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `complex_type`, `image_path`, `unit_count`, `facilities`, `latitude`, `longitude`, `description`, `address`, `owner_id`, `total_members`, `total_requests`, `pending_requests`, `created_at`) VALUES
(11, 'مجمع رسن 1', NULL, 'uploads/units/unit_1762356244_a77fbdd0.jpg', NULL, NULL, NULL, NULL, 'وحدة سكنية في مجمع رسن بالرياض', 'الرياض - حي الياسمين', 1, 120, 56, 3, '2025-11-05 07:57:50'),
(12, 'عمارة نيارة 54', NULL, 'uploads/units/unit_1762357057_f1776eb9.jpg', NULL, NULL, NULL, NULL, 'عمارة سكنية تحتوي على 40 شقة', 'الرياض - حي النرجس', 1, 88, 32, 1, '2025-11-05 07:57:50'),
(13, ' عمارة سدو 2\n            ', NULL, 'uploads/units/63384012_79f8a867886543.jpg', NULL, NULL, NULL, NULL, 'مجمع صغير للعائلات', 'جدة - حي الروضة', 1, 70, 45, 5, '2025-11-05 07:57:50'),
(14, 'مجمع رسن 4', NULL, 'uploads/units/unit_1762356002_b02b3d76.jpg', NULL, NULL, NULL, NULL, 'وحدة جديدة ضمن المرحلة الثانية للمشروع', 'الرياض - حي الصحافة', 1, 95, 64, 2, '2025-11-05 07:57:50'),
(15, 'مجمع رسن 5', NULL, NULL, NULL, NULL, NULL, NULL, 'عمارة راقية تضم 12 طابقًا', 'الدمام - حي الشاطئ', 5, 103, 176, 2, '2025-11-05 07:57:50'),
(16, 'مجمع رسن 6', NULL, NULL, NULL, NULL, NULL, NULL, 'مبنى إداري وسكني مشترك', 'الخبر - طريق الأمير فيصل', 6, 150, 90, 5, '2025-11-05 07:57:50'),
(17, 'مجمع رسن 7', NULL, NULL, NULL, NULL, NULL, NULL, 'عمارة متوسطة الحجم', 'الرياض - حي الربيع', 7, 82, 42, 3, '2025-11-05 07:57:50'),
(18, 'مجمع رسن 8', NULL, NULL, NULL, NULL, NULL, NULL, 'وحدة مميزة قريبة من الخدمات', 'الرياض - حي الملقا', 8, 60, 30, 0, '2025-11-05 07:57:50'),
(19, 'مجمع رسن 9', NULL, NULL, NULL, NULL, NULL, NULL, 'مجمع سكني متكامل المرافق', 'مكة - حي العوالي', 9, 134, 77, 6, '2025-11-05 07:57:50'),
(20, 'مجمع رسن 10', NULL, NULL, NULL, NULL, NULL, NULL, 'عمارة تحت إدارة اتحاد الملاك', 'الرياض - حي الورود', 10, 50, 20, 1, '2025-11-05 07:57:50'),
(22, 'test5', 'فلل', 'uploads/units/unit_1762356244_a77fbdd0.jpg', 9, '[\"مسبح\",\"نادي صحي\"]', NULL, NULL, NULL, NULL, 4, 0, 0, 0, '2025-11-05 18:24:04'),
(23, 'رسن 77', NULL, 'uploads/units/unit_1762357057_f1776eb9.jpg', NULL, NULL, NULL, NULL, 'وحدة عقارية من شركة رسن', 'الرياض - حي السعادة', 3, 22, 10, 2, '2025-11-05 18:37:37'),
(24, 'عمارة وتن (تحت الانشاء)', NULL, 'uploads/units/unit_1763384012_79f8a82a.png', NULL, NULL, NULL, NULL, 'وفق معايير كود وادي حنيفة', 'الدرعية - الحزاوي', 1, 9, 0, 0, '2025-11-17 15:53:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Email` varchar(70) NOT NULL,
  `Picture_Filename` varchar(50) DEFAULT NULL,
  `Password` varchar(32) NOT NULL,
  `Register_Date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `Name`, `Email`, `Picture_Filename`, `Password`, `Register_Date`) VALUES
(1, 'منسق مميز', 'test@test.com', NULL, '098f6bcd4621d373cade4e832627b4f6', '2025-11-04 12:50:03'),
(2, 'محمد الفيصل', 'mohammed.alfaisal@example.com', 'avatar1.jpg', '8d969eef6ecad3c29a3a629280e686cf', '2025-01-02 10:15:00'),
(3, 'هيفاء العلي', 'haifa.alali@example.com', 'avatar2.jpg', '481f6cc0511143ccdd7e2d1b1b94faf0', '2025-01-03 09:10:00'),
(4, 'أحمد الدوسري', 'ahmed.dosari@example.com', 'avatar3.jpg', '5e884898da28047151d0e56f8dc62927', '2025-01-05 14:25:00'),
(5, 'نورة السبيعي', 'nora.subaie@example.com', 'avatar4.jpg', '8579d18ca272b4cb6033ccb8919cb357', '2025-01-06 16:45:00'),
(6, 'عبدالله القحطاني', 'abdullah.qahtani@example.com', 'avatar5.jpg', '601cf460d3fa6d01875d72c106b239f1', '2025-01-08 11:20:00'),
(7, 'سارة الغامدي', 'sara.ghamdi@example.com', 'avatar6.jpg', '9b09700b7dfb6111ae19adeadaaedc7a', '2025-01-09 13:55:00'),
(8, 'فهد الحربي', 'fahad.harbi@example.com', 'avatar7.jpg', '6de4ba73218eb39e92101fae1d41189c', '2025-01-10 17:30:00'),
(9, 'ليان الشهري', 'layan.shahri@example.com', 'avatar8.jpg', '9166a9fbfb2ed29bf82adf47cc2c443d', '2025-01-12 08:05:00'),
(10, 'عبدالرحمن المطيري', 'abdulrahman.mutairi@example.com', 'avatar9.jpg', '2beb225ee77bc234c3c6d1f685f951b1', '2025-01-14 19:40:00'),
(11, 'ريم العبدالله', 'reem.abdullah@example.com', 'avatar10.jpg', 'ee0576d032d87cebd263b335b3c60137', '2025-01-15 21:15:00'),
(12, 'text', 'text@text.com', NULL, '098f6bcd4621d373cade4e832627b4f6', '2025-11-05 18:55:15'),
(13, 'Eng. Mohannad Fahad', 'engmohannadb@gmail.com', NULL, '', '2025-11-18 16:40:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrators`
--
ALTER TABLE `administrators`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_Category_Admin` (`Creator`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unit_id` (`unit_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_Messages_Sender` (`Sender`);

--
-- Indexes for table `pictures`
--
ALTER TABLE `pictures`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_Pictures_Category` (`Category_ID`),
  ADD KEY `FK_Pictures_Owner` (`Owner_ID`),
  ADD KEY `FK_Pictures_Album` (`Album_ID`),
  ADD KEY `FK_Pictures_DesignProgram` (`Design_Program`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_requests_units` (`unit_id`);

--
-- Indexes for table `request_comments`
--
ALTER TABLE `request_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_request` (`request_id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrators`
--
ALTER TABLE `administrators`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `pictures`
--
ALTER TABLE `pictures`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `request_comments`
--
ALTER TABLE `request_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `FK_Category_Admin` FOREIGN KEY (`Creator`) REFERENCES `administrators` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `chats_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `events_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`ID`) ON DELETE SET NULL;

--
-- Constraints for table `pictures`
--
ALTER TABLE `pictures`
  ADD CONSTRAINT `FK_Pictures_Album` FOREIGN KEY (`Album_ID`) REFERENCES `albums` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Pictures_Category` FOREIGN KEY (`Category_ID`) REFERENCES `category` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Pictures_DesignProgram` FOREIGN KEY (`Design_Program`) REFERENCES `design_programs` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Pictures_Owner` FOREIGN KEY (`Owner_ID`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `fk_requests_units` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `request_comments`
--
ALTER TABLE `request_comments`
  ADD CONSTRAINT `fk_request` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`) ON DELETE SET NULL;

--
-- Constraints for table `units`
--
ALTER TABLE `units`
  ADD CONSTRAINT `units_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`ID`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
