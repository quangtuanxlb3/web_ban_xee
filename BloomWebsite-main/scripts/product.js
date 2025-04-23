const citySelect = document.getElementById("city-select");
const districtSelect = document.getElementById("district-select");

const districts = {
    HANOI: [
    "Ba Đình", "Bắc Từ Liêm", "Cầu Giấy", "Đống Đa", "Hà Đông", "Hai Bà Trưng",
    "Hoàn Kiếm", "Hoàng Mai", "Long Biên", "Nam Từ Liêm", "Tây Hồ", "Thanh Xuân",
    "Ba Vì", "Chương Mỹ", "Đan Phượng", "Đông Anh", "Gia Lâm", "Hoài Đức",
    "Mê Linh", "Mỹ Đức", "Phú Xuyên", "Phúc Thọ", "Quốc Oai", "Sóc Sơn",
    "Thạch Thất", "Thanh Oai", "Thanh Trì", "Thường Tín", "Ứng Hòa", "Sơn Tây"
    ],
    HCM: [
    "Quận 1", "Quận 3", "Quận 4", "Quận 5", "Quận 6", "Quận 7", "Quận 8",
    "Quận 10", "Quận 11", "Quận 12", "Bình Tân", "Bình Thạnh", "Gò Vấp",
    "Phú Nhuận", "Tân Bình", "Tân Phú", "Bình Chánh", "Cần Giờ", "Củ Chi",
    "Hóc Môn", "Nhà Bè", "Thành phố Thủ Đức"
    ]
};
  
citySelect.addEventListener("change", function () {
    const selectedCity = this.value;
    const districtOptions = districts[selectedCity] || [];

    districtSelect.innerHTML = '<option value="">-- Chọn quận/huyện --</option>';

    districtOptions.forEach(function (district) {
        const option = document.createElement("option");
        option.value = district;
        option.textContent = district;
        districtSelect.appendChild(option);
    });
});