/* ================= ADMIN — shared inquiry demo data =================
   One dataset, loaded before admin-inquiries.js (list) and
   admin-inquiry-detail.js (detail), so a status change or note added on one
   screen is reflected if the other is opened in the same tab session. No
   backend yet — see TASKS.md L1.1 (inquiries + inquiry_notes tables) for the
   real version. */
window.RHL_INQUIRIES = [
  { id: 'INQ-1042', name: 'Farida Yasmin', phone: '+880 1812-345678', email: 'farida.y@gmail.com', project: 'Banani Lake Residences', message: 'Interested in a 3-bed duplex facing the courtyard. Could you share the current payment schedule and expected handover date?', status: 'new', date: '2026-08-24', notes: [] },
  { id: 'INQ-1041', name: 'Shakil Ahmed', phone: '+880 1911-223344', email: 'shakil.ahmed@yahoo.com', project: 'RHL Trade Centre', message: 'Looking for a full floor of Grade-A office space, ideally on the higher floors. What is the current leasing rate per sq ft?', status: 'contacted', date: '2026-08-23', notes: [
    { author: 'Farhana Islam', text: 'Called and shared the leasing brochure. Following up next week with floor availability.', date: '2026-08-23 4:10 PM' }
  ] },
  { id: 'INQ-1040', name: 'Nusrat Jahan', phone: '+880 1711-998877', email: 'nusrat.jahan@outlook.com', project: 'Gulshan Heights', message: 'Is unit 6B still available? We viewed it in June and would like an updated price.', status: 'follow-up', date: '2026-08-22', notes: [
    { author: 'Tanvir Huda', text: 'Unit 6B sold in July. Offered 8A as an alternative with similar layout — awaiting her decision.', date: '2026-08-22 11:05 AM' }
  ] },
  { id: 'INQ-1039', name: 'Imran Kabir', phone: '+880 1611-556677', email: 'imran.kabir@gmail.com', project: 'Aurora Waterfront Villas', message: 'Ready to proceed with booking Villa 4. Please send the booking form and account details.', status: 'converted', date: '2026-08-20', notes: [
    { author: 'Farhana Islam', text: 'Booking form sent, 20% down payment received. Handover to sales agreement stage.', date: '2026-08-21 2:30 PM' }
  ] },
  { id: 'INQ-1038', name: 'Rashida Begum', phone: '+880 1516-778899', email: 'rashida.begum@gmail.com', project: 'Dhanmondi Garden Villas', message: 'Just browsing — wanted to know if any villas remain unsold.', status: 'closed', date: '2026-08-15', notes: [
    { author: 'Tanvir Huda', text: 'All 12 villas sold; informed the customer and suggested Lakeview Court instead.', date: '2026-08-16 9:40 AM' }
  ] },
  { id: 'INQ-1037', name: 'Kamal Hossain', phone: '+880 1911-445566', email: 'kamal.h@rediffmail.com', project: 'Tejgaon Industrial Park', message: 'Would like to lease two adjoining blocks for a distribution business. Please call to discuss terms.', status: 'contacted', date: '2026-08-14', notes: [] },
  { id: 'INQ-1036', name: 'Sabrina Chowdhury', phone: '+880 1722-334455', email: 'sabrina.c@gmail.com', project: 'Lakeview Court', message: 'What is the expected launch price for the 3-bed units?', status: 'new', date: '2026-08-13', notes: [] },
  { id: 'INQ-1035', name: 'Mahmudul Hasan', phone: '+880 1812-667788', email: 'mahmudul.hasan@yahoo.com', project: 'Banani Corporate Tower', message: 'Our firm needs 15,000 sq ft of office space with immediate availability. Any current vacancies?', status: 'converted', date: '2026-08-10', notes: [
    { author: 'Farhana Islam', text: 'Lease signed for floors 14–15. Closed.', date: '2026-08-12 3:00 PM' }
  ] },
  { id: 'INQ-1034', name: 'Tania Rahman', phone: '+880 1611-990011', email: 'tania.rahman@gmail.com', project: 'RHL Trade Centre', message: 'Can you share floor plans for the 15th–18th floor range?', status: 'closed', date: '2026-08-05', notes: [] },
  { id: 'INQ-1033', name: 'Jahangir Alam', phone: '+880 1516-223311', email: 'jahangir.alam@gmail.com', project: 'Gulshan Park Avenue', message: 'Register my interest for a 4-bed residence once the project launches formally.', status: 'follow-up', date: '2026-07-30', notes: [] }
];
