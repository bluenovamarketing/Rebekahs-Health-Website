<?php
/** Initial migration from the approved Our Team page plus six client-reviewed additions. */

defined( 'ABSPATH' ) || exit;

$rhn_bio = static function ( ...$paragraphs ) {
	return implode( "\n\n", $paragraphs );
};

return array(
	array(
		'key' => 'angela-basner', 'name' => 'Angela Basner', 'store' => 'lapeer', 'role' => 'Store Manager · Transformational Life Coach', 'order' => 10, 'position' => 'upper',
		'summary' => 'Angela brings compassion, practical guidance and a whole-person perspective to every customer conversation.',
		'alt' => "Angela Basner, Store Manager at Rebekah's Lapeer", 'image' => 'theme:output/our-team/assets/angie.jpg',
		'bio' => $rhn_bio(
			"Angela Basner is the Store Manager at Rebekah's Health & Nutrition in Lapeer and a certified Transformational Life Coach with a passion for natural wellness. She enjoys helping customers explore personalized options through nutrition, supplements and healthy lifestyle choices.",
			'Angela believes wellness begins within and includes nourishing the mind, body and spirit. She is dedicated to educating, empowering and encouraging others with compassion and practical guidance.',
			'Outside of work, Angela enjoys hiking, kayaking, exploring nature, reading about holistic health, traveling and spending quality time with her family. She is passionate about lifelong learning and inspiring others to live healthier, more balanced and meaningful lives.'
		),
	),
	array(
		'key' => 'pamela-stuewer', 'name' => 'Pamela Stuewer', 'store' => 'lapeer', 'role' => 'Wellness Coach', 'order' => 20, 'position' => 'top',
		'summary' => 'For Pam, the best part of the day is helping people become more aware of themselves and their natural wellness options.',
		'alt' => "Pamela Stuewer, Wellness Coach at Rebekah's Lapeer", 'image' => 'theme:output/our-team/assets/pam.jpg',
		'bio' => $rhn_bio(
			"Rebekah's Health & Nutrition has molded me into the person I am today.",
			'Nine years of co-workers, customers, products, challenges, tears and smiles—it all works together to make us more passionate about our health.',
			'The best part of my day is helping others become more aware of themselves and the natural alternatives available.'
		),
	),
	array(
		'key' => 'ashlyn', 'name' => 'Ashlyn', 'store' => 'lapeer', 'role' => 'Wellness Coach', 'order' => 30, 'position' => 'center',
		'summary' => "Ashlyn's time at Rebekah's has grown an interest in holistic health into a lifelong passion for learning.",
		'alt' => "Ashlyn, Wellness Coach at Rebekah's Lapeer", 'image' => 'theme:output/our-team/assets/ashlyn.jpg',
		'bio' => $rhn_bio(
			"Hi, I'm Ashlyn! Throughout my employment at Rebekah's Health & Nutrition, I've developed more than an interest in holistic health—I've found a lifelong passion.",
			'In the future, I plan to pursue higher education with the goal of becoming a compounding pharmacist specializing in holistic health. Right now, I continue expanding my knowledge of natural wellness while pursuing other passions such as babysitting and coaching youth cheerleading.',
			"I'm beyond grateful for the team members, customers and community members who have supported me on my path of learning and growth. I look forward to what the future holds and am honored to be part of the Rebekah's team!"
		),
	),
	array(
		'key' => 'mark', 'name' => 'Mark', 'store' => 'lapeer', 'role' => 'Head Buyer', 'order' => 40, 'position' => 'center', 'image_class' => 'portrait-mark',
		'summary' => 'Mark researches supplements and emerging wellness needs so the Lapeer community can find thoughtfully selected products.',
		'alt' => "Mark, Head Buyer at Rebekah's Lapeer, with his family", 'image' => 'content:uploads/2026/08/mark.jpg',
		'bio' => $rhn_bio(
			'Health and wellness has become more than a career for me—it is something I am genuinely passionate about. As the product purchaser for a health and wellness store, I spend my days researching supplements, learning about new health trends and working to make sure our community has access to products that are safe, effective and truly needed.',
			'Outside of work, I am a proud father of five amazing kids. Being a parent has given me a deeper appreciation for the importance of helping families make informed decisions about their health. It reminds me every day why the work I do matters.',
			'I believe a wellness store should be more than a place to shop—it should be a trusted resource for the community. Whether it is preparing for seasonal wellness needs, finding high-quality supplements or staying ahead of emerging wellness trends, my goal is to make sure our shelves are stocked with products that meet the needs of our customers.',
			'For me, it is never just about the products. It is about caring for families, supporting our neighbors and helping build a healthier community—one person at a time.'
		),
	),
	array(
		'key' => 'victoria', 'name' => 'Victoria', 'store' => 'lapeer', 'role' => 'Wellness Coach', 'order' => 50, 'position' => 'center', 'image_class' => 'portrait-victoria',
		'summary' => 'Victoria brings a lifelong connection to holistic wellness and a love of herbs, natural foods and everyday wellness practices.',
		'alt' => "Victoria, Wellness Coach at Rebekah's Lapeer, outdoors with her husband", 'image' => 'content:uploads/2026/08/victoria.jpg',
		'bio' => $rhn_bio(
			'Hello! My name is Victoria, and I have been working at Rebekah’s for over two years. My family has taken a holistic approach to health since I was a child, but I developed my own personal interest in natural wellness almost a decade ago.',
			'My favorite herbal ingredient is raw garlic. I love learning about traditional uses for herbs and incorporating natural foods and wellness practices into everyday life.',
			'In my free time, you might find me making kombucha or sourdough, painting, spending time with farm animals, or trying a new restaurant or coffee shop with my husband.'
		),
	),
	array(
		'key' => 'kendra', 'name' => 'Kendra', 'store' => 'lapeer', 'role' => 'Wellness Coach', 'order' => 60, 'position' => 'center', 'bundle' => 'kendra.jpg',
		'summary' => 'Kendra brings curiosity shaped by personal experience and a love of natural wellness, family, gardening and the outdoors.',
		'alt' => "Kendra, Wellness Coach at Rebekah's Lapeer", 'image' => 'plugin:assets/profiles/kendra.jpg',
		'bio' => $rhn_bio(
			'Hi, I’m Kendra. I’m passionate about guiding people on their journey to holistic health. My own medical challenges, and those of my loved ones, sparked a deep curiosity to learn everything I can about natural wellness and the healing remedies God has placed on this earth.',
			'I love spending time outdoors, whether I’m tending my flower and herb garden, caring for my ducks and chickens, exploring new places with my family, or enjoying time with friends.',
			'For more than a year, I’ve worked with Rebekah’s amazing team. I’ve loved connecting with my coworkers, getting to know our customers, and helping people take meaningful steps toward better health.'
		),
	),
	array(
		'key' => 'ava', 'name' => 'Ava', 'store' => 'lapeer', 'role' => 'Wellness Coach', 'order' => 70, 'position' => 'center', 'bundle' => 'ava.jpg',
		'summary' => 'Ava pairs graduate study in applied sport psychology with a passion for natural living, holistic health and athlete recovery.',
		'alt' => "Ava, Wellness Coach at Rebekah's Lapeer", 'image' => 'plugin:assets/profiles/ava.jpg',
		'bio' => $rhn_bio(
			'Hi, my name is Ava! I am currently in graduate school earning my master’s degree in applied sport psychology. I have always loved natural living and a holistic approach to health. I hope to one day bring natural practices into the sport psychology world and introduce new ways athletes can support their body systems and recovery.',
			'I love to travel and have a passion for music. If I’m not at Rebekah’s, you’ll probably find me at a baseball game, concert, or sightseeing.'
		),
	),
	array(
		'key' => 'todd-cochell', 'name' => 'Todd Cochell', 'store' => 'grand-blanc', 'role' => 'Store Manager', 'order' => 10, 'position' => 'upper',
		'summary' => 'Todd brings more than 25 years of health-food industry experience and an education-first approach to Grand Blanc.',
		'alt' => "Todd Cochell, Store Manager at Rebekah's Grand Blanc", 'image' => 'theme:output/our-team/assets/todd.jpg',
		'bio' => $rhn_bio(
			"Todd Cochell brings more than 25 years of health-food industry experience to his role as Store Manager at Rebekah's Health & Nutrition in Grand Blanc.",
			'His career began in health food stores, where a desire to help people and a passion for natural wellness grew into a lifelong commitment to learning. Over the years, Todd has continued to expand his knowledge through research, education and the experiences shared by the people he has served.',
			'From 2016 to 2020, Todd worked with Garden of Life as a Product Specialist and Educator. That experience further strengthened his understanding of supplements, nutrition and the importance of helping customers make informed choices for their individual wellness goals.',
			"Todd is proud to be part of Rebekah's, a company whose values align closely with his own: education, integrity and genuine care for the community. He looks forward to meeting new customers, sharing what he has learned and helping create a welcoming place where people feel comfortable asking questions."
		),
	),
	array(
		'key' => 'jackelyn', 'name' => 'Jackelyn', 'store' => 'grand-blanc', 'role' => 'Holistic Health Practitioner', 'order' => 20, 'position' => 'center',
		'summary' => "A lifelong love of nourishing food, family wellness and research led Jackelyn naturally to Rebekah's.",
		'alt' => "Jackelyn, Holistic Health Practitioner at Rebekah's Grand Blanc", 'image' => 'theme:output/our-team/assets/jackelyn.jpg',
		'bio' => $rhn_bio(
			'Growing up in a health-conscious family, eating nourishing foods was always part of my life. I was free to experiment with recipes, which began a love of cooking and naturally led to adapting dishes to be more healthful.',
			'Becoming a mother brought a heightened awareness of raising healthy, thriving children. Being a mother of seven, with four babies born at home, also led me to learn more about prenatal and postnatal wellness.',
			"Instinctively a researcher, I began diving deeper into alternative health. Seeing positive changes in my family's daily wellness strengthened my desire to help others, and my employment at Rebekah's was a perfect fit.",
			'The education provided through classes and becoming a certified Holistic Health Practitioner have given me more confidence in supporting customers, friends and family as they explore informed wellness choices.',
			"In my free time, I love taking nature walks with my family, exploring the plants and herbs along the way and appreciating God's creation. I love the life my husband and I have created and find joy in helping others feel supported in theirs."
		),
	),
	array(
		'key' => 'debbie', 'name' => 'Debbie', 'store' => 'grand-blanc', 'role' => 'Holistic Health Practitioner · Wellness Coach', 'order' => 30, 'position' => 'top',
		'summary' => 'Debbie pairs more than 25 years of personal wellness study with a caring, individualized approach.',
		'alt' => "Debbie, Holistic Health Practitioner at Rebekah's Grand Blanc", 'image' => 'theme:output/our-team/assets/debbie.jpg',
		'bio' => $rhn_bio(
			"I am a Holistic Health Practitioner, dietary supplement specialist and wellness coach at Rebekah's Health & Nutrition.",
			'I began studying and practicing wellness more than 25 years ago because I wanted a better quality of health for myself. That personal journey grew into a lifelong passion for helping others make informed, practical choices that support their own wellness goals.',
			'My career began as a hairdresser and in-salon educator, where I discovered how much I enjoyed helping people look and feel their best. From there, I continued building on that passion through ongoing education in holistic health, nutrition and supplements.',
			"Today, I bring that same caring, individualized approach to customer consultations at Rebekah's. I enjoy listening to each person's needs and helping them explore wellness options that fit their lifestyle."
		),
	),
	array(
		'key' => 'brian', 'name' => 'Brian', 'store' => 'grand-blanc', 'role' => 'Holistic Health Practitioner', 'order' => 40, 'position' => 'center',
		'summary' => 'Brian brings creativity, curiosity and a warm, thoughtful presence to each customer conversation.',
		'alt' => "Brian, Holistic Health Practitioner at Rebekah's Grand Blanc", 'image' => 'theme:output/our-team/assets/brian.jpg',
		'bio' => $rhn_bio(
			"Brian has been a valued member of the Rebekah's Health & Nutrition Grand Blanc team since 2024. He brings a warm, creative and thoughtful approach to helping customers explore products and practices that support their individual wellness routines.",
			'In 2025, Brian earned his certification as a Holistic Health Practitioner. He has also completed hundreds of hours of education in nutrition, supplements and herbs, allowing him to share knowledgeable, practical product information with customers.',
			'With interests in wellness coaching, essential oils, homeopathy, crystals, mindfulness and self-care, Brian enjoys creating a welcoming space where people feel comfortable asking questions and learning about new options. He is also a musician, creative artist and entrepreneur, bringing curiosity and heart into everything he does.',
			'Brian believes wellness looks different for every person. His goal is to listen, share educational information and help customers feel supported as they make choices that align with their own goals and lifestyle.'
		),
	),
	array(
		'key' => 'margaret', 'name' => 'Margaret', 'store' => 'clarkston', 'role' => 'Store Manager · Holistic Nutritionist', 'order' => 10, 'position' => 'center',
		'summary' => 'Margaret loves building customer relationships and is continuing her education in holistic nutrition.',
		'alt' => "Margaret, Store Manager at Rebekah's Clarkston", 'image' => 'theme:output/our-team/assets/margaret.jpg',
		'bio' => $rhn_bio(
			"Hi, I'm Margaret! I've been part of the Rebekah's Health & Nutrition family for the past five years and currently manage our Clarkston location. I love building relationships with our customers and helping them pursue their health and wellness goals.",
			"My passion for natural health has grown through both hands-on experience and education. I recently completed my schooling and am now a Holistic Nutritionist. I'm currently studying to become a Board Certified Holistic Nutritionist (BCHN) so I can continue expanding my knowledge and better serve those in our community.",
			"Looking ahead, my dream is to build a business of my own while continuing to make a positive impact in the health and wellness field. I'm excited for what the future holds and grateful to be part of a team that shares my passion for helping others live healthier, happier lives."
		),
	),
	array(
		'key' => 'catie', 'name' => 'Catie', 'store' => 'clarkston', 'role' => 'Store Purchaser', 'order' => 20, 'position' => 'center', 'image_class' => 'portrait-native', 'media_class' => 'has-native',
		'summary' => 'Catie loves discovering new products, following nutrition trends and building a career centered on health and fitness.',
		'alt' => "Catie, Store Purchaser at Rebekah's Clarkston", 'image' => 'theme:output/our-team/assets/catie.jpg',
		'bio' => $rhn_bio(
			"Hi, I'm Catie! I've been part of the Rebekah's Health & Nutrition family for almost three years. I started as a cashier and worked my way into my current role as the store's purchaser. I love discovering new products, staying on top of the latest trends in health and nutrition and helping ensure our stores have the best selection for our customers.",
			"My passion for health and fitness continues to grow through both hands-on experience and education. I'm currently working toward my NASM Certified Personal Trainer and Nutrition Coach certifications to expand my knowledge and better help others reach their health and wellness goals.",
			"Looking ahead, I hope to continue growing with Rebekah's while building a career centered around health, fitness and helping people live healthier lives. I'm grateful to be part of a team that's passionate about making a positive impact in our community."
		),
	),
	array(
		'key' => 'jasmine', 'name' => 'Jasmine', 'store' => 'clarkston', 'role' => 'Wellness Coach', 'order' => 30, 'position' => 'center', 'bundle' => 'jasmine.jpg',
		'summary' => 'Jasmine brings a background in floral design and store management, plus a growing passion for plants, traditional herbal wellness and education.',
		'alt' => "Jasmine, Wellness Coach at Rebekah's Clarkston", 'image' => 'plugin:assets/profiles/jasmine.jpg',
		'bio' => $rhn_bio(
			'Jasmine is a member of our Clarkston team. Born and raised in Michigan, she is currently a business student with a background in both floral design and store management. During her career as a florist, she developed a deep appreciation for plants and became increasingly interested in plant-based wellness.',
			'That curiosity led her to begin researching traditional plant medicine and learning from traditional healers in both Central America and Michigan. Her love of flowers naturally grew into a passion for herbal wellness, education, and helping others explore natural approaches to health.',
			'Outside of work, Jasmine enjoys crocheting, hiking, yoga, and reading books focused on personal growth and plant medicine.',
			'With her love of plants, eagerness to learn, and genuine desire to help others, Jasmine is excited to grow in her role as one of our Wellness Coaches. Be sure to stop by our Clarkston location and say hello!'
		),
	),
	array(
		'key' => 'mikayla', 'name' => 'Mikayla', 'store' => 'lake-orion', 'role' => 'Manager · Purchaser', 'order' => 10, 'position' => 'center',
		'summary' => 'Mikayla combines ongoing product education with graduate study in clinical mental health counseling and a thoughtful mind-and-body perspective.',
		'alt' => "Mikayla, Manager and Purchaser at Rebekah's Lake Orion", 'image' => 'theme:output/our-team/assets/mikayla.jpg',
		'bio' => $rhn_bio(
			"We are excited to introduce Mikayla, the Manager and Purchaser of Rebekah's Health & Nutrition in Lake Orion.",
			"Mikayla has a true passion for health, wellness and helping people feel their best. One of her favorite parts of working at Rebekah's is getting to know our customers and helping them discover products that fit their individual goals, lifestyles and needs.",
			'Whether you are looking for vitamins, natural wellness products, healthier food options or simply have questions about where to begin, Mikayla is always happy to help.',
			'She has completed product education and certification programs through companies including Terry Naturally, Panoram, Host Defense and others. Continuing education helps her stay informed about new products, ingredients and wellness education. Her goal is never simply to sell a product—it is to help each person make an informed decision they feel good about.',
			"Mikayla is also pursuing her master's degree in Clinical Mental Health Counseling at Oakland University. Her education has strengthened her belief that wellness includes caring for both the mind and body, and she brings that thoughtful perspective into the store every day.",
			'We are grateful to have Mikayla leading our Lake Orion location. Stop in, say hello and let her help you explore your next wellness step with confidence.'
		),
	),
	array(
		'key' => 'tj-lawson', 'name' => 'TJ Lawson', 'store' => 'lake-orion', 'role' => 'Lead Key Team Member', 'order' => 20, 'position' => 'center', 'bundle' => 'tj.jpg',
		'summary' => 'TJ brings patience, compassion and a community-minded approach shaped by supporting children, seniors and families.',
		'alt' => "TJ Lawson, Lead Key Team Member at Rebekah's Lake Orion", 'image' => 'plugin:assets/profiles/tj.jpg',
		'bio' => $rhn_bio(
			'Hi, I’m TJ Lawson. I’ve called the area home for the past five years, and as a new father to a six-month-old girl, I’m especially invested in being part of a community where families can grow, connect, and thrive. I’m excited to become even more involved through my role at Rebekah’s Health & Nutrition.',
			'I genuinely enjoy meeting people, communicating, and sharing knowledge that can help others. Throughout my life, I’ve had the opportunity to work with and support children on the autism spectrum as well as provide care and assistance to seniors. Those experiences have taught me the importance of patience, compassion, listening, and treating every person as an individual.',
			'I’m a people person at heart, and I believe strong communities are built through genuine connections and people looking out for one another. I’m looking forward to getting to know more of our local businesses and neighbors, sharing what Rebekah’s has to offer, and being a positive and helpful part of the community I’m proud to call home.'
		),
	),
	array(
		'key' => 'claire', 'name' => 'Claire', 'store' => 'lake-orion', 'role' => 'Wellness Coach', 'order' => 30, 'position' => 'upper', 'bundle' => 'claire.jpg',
		'summary' => 'Claire is known for listening carefully, sharing practical product knowledge and helping customers feel confident about their wellness options.',
		'alt' => "Claire, Wellness Coach at Rebekah's Lake Orion", 'image' => 'plugin:assets/profiles/claire.jpg',
		'bio' => $rhn_bio(
			'Claire has a genuine passion for clean, high-quality products and truly believes in what Rebekah’s Health & Nutrition stands for—purity, education, and helping people make informed choices for their health and wellness.',
			'Her knowledge continues to grow every day, and she has become especially strong in product education related to comfort, women’s wellness, and a wide variety of everyday needs.',
			'What makes Claire so special is the way she takes time with each person. She listens, she cares, and she never wants you to feel rushed. Her goal is to help you better understand your options and feel confident in the choices you make for yourself and your family.',
			'Claire also believes in many of the products she recommends because she uses them herself and can share her own personal experiences. Whether you have a question, need help finding the right product, or simply want to learn something new, Claire is always happy to help and will greet you with kindness, compassion, and a genuine desire to support your wellness journey.'
		),
	),
	array(
		'key' => 'liam', 'name' => 'Liam', 'store' => 'lake-orion', 'role' => 'Wellness Coach', 'order' => 40, 'position' => 'center', 'bundle' => 'liam.jpg',
		'summary' => 'Liam brings a dependable work ethic, a willingness to learn and a steady commitment to supporting customers and teammates.',
		'alt' => "Liam, Wellness Coach at Rebekah's Lake Orion", 'image' => 'plugin:assets/profiles/liam.jpg',
		'bio' => $rhn_bio(
			'Liam has been part of the Rebekah’s Health & Nutrition team for over two years and has become a valued member of our team.',
			'He is always willing to help, learn something new, and step in wherever he is needed. Liam’s dependable nature, strong work ethic, and willingness to support both his teammates and our customers make him an important part of the store.',
			'We truly appreciate the dedication Liam brings each day and are grateful to have him as part of the Rebekah’s family. Be sure to say hello to Liam the next time you visit!'
		),
	),
);
