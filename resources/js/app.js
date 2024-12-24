require("process");

require("./bootstrap");

// Make sure userID is defined
const userID = window.Laravel?.user?.id;

// Add error handling and debugging
var channel = Echo.private(`notifications.${userID}`);

// Debug channel connection
channel.error((error) => {
    console.error("Channel error:", error);
});

channel.listen(".new-booking", function (data) {
    console.log("New booking event received:", data);
    console.log("UserID:", userID);

    // تنسيق التاريخ بشكل جميل
    const startDate = new Date(data.start_date).toLocaleDateString();
    const endDate = new Date(data.end_date).toLocaleDateString();
    // إنشاء رسالة الإشعار
    const message = `
        New Booking By: ${data.user_name}
        From: ${startDate}
        To: ${endDate}
        And Total Amount: $${data.total_amount}
    `.trim();

    // استخدام دالة Toastar بدلاً من toastr.success مباشرة
    Toastar(message, "New Booking");
});
