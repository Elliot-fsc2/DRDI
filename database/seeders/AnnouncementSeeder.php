<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user as author, or create one if none exists
        $author = User::first() ?? User::factory()->create([
            'name' => 'Department Admin',
            'email' => 'admin@department.edu',
        ]);

        $announcements = [
            [
                'title' => '🎓 Thesis Defense Schedule Updated',
                'content' => 'Dear students and faculty,

We have updated the thesis defense schedule for this semester. All defense sessions will now be conducted virtually through our online platform.

Key changes:
• All defenses will be held via Zoom
• Recording will be available for review
• Extended Q&A sessions (30 minutes each)

Please check your individual schedules and ensure your presentations are ready. Contact your advisor if you have any technical concerns.

Best regards,
Department Administration',
                'is_pinned' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => '📚 New Research Resources Available',
                'content' => 'We are excited to announce that our department now has access to several new research databases and journals:

• IEEE Xplore Digital Library
• ACM Digital Library
• ScienceDirect
• JSTOR Archive Collection

These resources are available to all enrolled students and faculty members. Access credentials can be obtained from the department office.

Happy researching! 📖',
                'is_pinned' => false,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => '🏆 Student Achievement Awards',
                'content' => 'Congratulations to our outstanding students who have been recognized for their academic excellence!

🥇 Best Thesis Award: Maria Santos - "Machine Learning Applications in Healthcare"
🥈 Excellence in Research: John Smith - "Sustainable Energy Solutions"
🥉 Innovation Award: Sarah Johnson - "AI-Powered Educational Tools"

The awards ceremony will be held next Friday at 3 PM in the main auditorium. All are welcome to attend!

Well done to all our achievers! 🎉',
                'is_pinned' => false,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => '📅 Important: Course Registration Deadline',
                'content' => 'This is a reminder that course registration for the next semester closes in 3 days.

Students must:
✅ Complete all prerequisite courses
✅ Maintain minimum GPA requirements
✅ Submit registration forms by Friday 5 PM

Late registrations will not be accepted. Contact your academic advisor if you need assistance.

Don\'t miss out on your preferred courses!',
                'is_pinned' => false,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => '🤝 Industry Partnership Program',
                'content' => 'We are thrilled to announce our new industry partnership program!

Leading tech companies are now offering:
• Internship opportunities
• Guest lectures from industry experts
• Collaborative research projects
• Job placement assistance

Companies participating:
• TechCorp Solutions
• InnovateLabs
• DataDriven Inc.
• FutureTech Systems

Students interested in participating should attend the information session this Wednesday at 2 PM in Room 301.',
                'is_pinned' => false,
                'published_at' => now()->subDays(12),
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create([
                'title' => $announcement['title'],
                'content' => $announcement['content'],
                'author_id' => $author->id,
                'is_pinned' => $announcement['is_pinned'],
                'published_at' => $announcement['published_at'],
                'is_active' => true,
            ]);
        }
    }
}
