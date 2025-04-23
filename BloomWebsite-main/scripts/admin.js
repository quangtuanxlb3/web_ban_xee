document.addEventListener('DOMContentLoaded', () => {
    
    const toggleBtn = document.querySelector('.toggle-btn');
    const sidebar = document.querySelector('.sidebar-container');
    
    toggleBtn.addEventListener('click', function() {
      sidebar.classList.toggle('sidebar-collapsed');
    });
    
    const menuItems = document.querySelectorAll('.menu-item');
    const contentSections = document.querySelectorAll('.content-section');
    
    menuItems.forEach(item => {
      item.addEventListener('click', function() {
        menuItems.forEach(i => i.classList.remove('active'));
        
        this.classList.add('active');
        
        const sectionId = this.getAttribute('data-section');
        contentSections.forEach(section => {
          section.classList.remove('active');
          if (section.id === sectionId) {
            section.classList.add('active');
          }
        });
      });
    });
    
    const addProductBtn = document.getElementById('add-product-btn');
    const cancelAddBtn = document.getElementById('cancel-add');
    
    if (addProductBtn) {
        addProductBtn.addEventListener('click', function() {
        menuItems.forEach(i => i.classList.remove('active'));
        const productAddMenuItem = document.querySelector('[data-section="product-add"]');
        if (productAddMenuItem) {
          productAddMenuItem.classList.add('active');
        }
        
        contentSections.forEach(section => {
          section.classList.remove('active');
          if (section.id === 'product-add') {
            section.classList.add('active');
          }
        });
      });
    }
    
    if (cancelAddBtn) {
      cancelAddBtn.addEventListener('click', function() {
        menuItems.forEach(i => i.classList.remove('active'));
        const productListMenuItem = document.querySelector('[data-section="product-list"]');
        if (productListMenuItem) {
          productListMenuItem.classList.add('active');
        }
        
        contentSections.forEach(section => {
          section.classList.remove('active');
          if (section.id === 'product-list') {
            section.classList.add('active');
          }
        });
      });
    }
    
    const addUserBtn = document.getElementById('add-user-btn');
    const cancelAddUserBtn = document.getElementById('cancel-add-user');
    
    if (addUserBtn) {
      addUserBtn.addEventListener('click', function() {
        menuItems.forEach(i => i.classList.remove('active'));
        const userAddMenuItem = document.querySelector('[data-section="user-add"]');
        if (userAddMenuItem) {
          userAddMenuItem.classList.add('active');
        }
        
        contentSections.forEach(section => {
          section.classList.remove('active');
          if (section.id === 'user-add') {
            section.classList.add('active');
          }
        });
      });
    }
    
    if (cancelAddUserBtn) {
      cancelAddUserBtn.addEventListener('click', function() {
        menuItems.forEach(i => i.classList.remove('active'));
        const userListMenuItem = document.querySelector('[data-section="user-list"]');
        if (userListMenuItem) {
          userListMenuItem.classList.add('active');
        }
        
        contentSections.forEach(section => {
          section.classList.remove('active');
          if (section.id === 'user-list') {
            section.classList.add('active');
          }
        });
      });
    }
    
    const addProductForm = document.getElementById('add-product-form');
    if (addProductForm) {
      addProductForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        
        fetch('/BloomWebsite/composables/products/add.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Thêm sản phẩm thành công!');
            this.reset();
            
            menuItems.forEach(i => i.classList.remove('active'));
            const productListMenuItem = document.querySelector('[data-section="product-list"]');
            if (productListMenuItem) {
              productListMenuItem.classList.add('active');
            }
            
            contentSections.forEach(section => {
              section.classList.remove('active');
              if (section.id === 'product-list') {
                section.classList.add('active');
              }
            });
            
            loadProducts(1);
          } else {
            alert('Lỗi: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Có lỗi xảy ra khi thêm sản phẩm');
        });
      });
    }
    
    const addUserForm = document.getElementById('add-user-form');
    console.log(addUserForm);
    
    if (addUserForm) {
      addUserForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const password = document.getElementById('user-password').value;
        const confirmPassword = document.getElementById('user-confirm-password').value;

        if (password !== confirmPassword) {
          alert('Mật khẩu xác nhận không khớp!');
          return;
        }

        const formData = new FormData(e.target);
        
        fetch('/BloomWebsite/composables/users/add.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Thêm người dùng thành công!');
            this.reset();
            
            menuItems.forEach(i => i.classList.remove('active'));
            const userListMenuItem = document.querySelector('[data-section="user-list"]');
            if (userListMenuItem) {
              userListMenuItem.classList.add('active');
            }
            
            contentSections.forEach(section => {
              section.classList.remove('active');
              if (section.id === 'user-list') {
                section.classList.add('active');
              }
            });
            
            loadUsers();
          } else {
            alert('Lỗi: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Có lỗi xảy ra khi thêm người dùng');
        });
      });
    }
    
    const paginationButtons = document.querySelectorAll('.pagination button');
    
    paginationButtons.forEach(button => {
      button.addEventListener('click', function() {
        const currentActive = button.parentElement.querySelector('.active');
        if (currentActive) {
          currentActive.classList.remove('active');
        }
        this.classList.add('active');
      });
    });
    
    const showMessage = (message, type) => {
      const alertElement = document.createElement('div');
      alertElement.className = `alert alert-${type}`;
      alertElement.textContent = message;
      
      document.querySelector('main').prepend(alertElement);
      
      setTimeout(() => {
        alertElement.remove();
      }, 5000);
    };
    
    if (typeof sessionMessages !== 'undefined' && sessionMessages.length > 0) {
      sessionMessages.forEach(msg => {
        showMessage(msg.text, msg.type);
      });
    }
    
    document.addEventListener('click', function(e) {
      if (e.target.classList.contains('edit-btn') || e.target.closest('.edit-btn')) {
        const btn = e.target.classList.contains('edit-btn') ? e.target : e.target.closest('.edit-btn');
        const productId = btn.getAttribute('data-id');
        const productName = btn.getAttribute('data-name');
        const productPrice = btn.getAttribute('data-price');
        const productDiscount = btn.getAttribute('data-discount');
        const productQuantity = btn.getAttribute('data-quantity');
        const productType = btn.getAttribute('data-type');
        const productImage = btn.getAttribute('data-image');
        const productDescription = btn.getAttribute('data-description');
        
        const editForm = document.getElementById('edit-product-form');
        if (editForm) {
          document.getElementById('edit-product-id').value = productId;
          document.getElementById('edit-product-name').value = productName;
          document.getElementById('edit-product-price').value = productPrice;
          document.getElementById('edit-product-discount').value = productDiscount;
          document.getElementById('edit-product-quantity').value = productQuantity;
          document.getElementById('edit-product-type').value = productType;
          document.getElementById('current-product-image').src = productImage;
          document.getElementById('edit-product-description').value = productDescription;
          
          document.getElementById('edit-product-modal').style.display = 'block';
        }
      }
    });
    
    const closeButtons = document.querySelectorAll('.close-modal');
    closeButtons.forEach(btn => {
      btn.addEventListener('click', function() {
        this.closest('.modal').style.display = 'none';
      });
    });
    
    const editProductForm = document.getElementById('edit-product-form');
    if (editProductForm) {
      editProductForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('/BloomWebsite/composables/products/update.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Cập nhật sản phẩm thành công!');
            document.getElementById('edit-product-modal').style.display = 'none';
            loadProducts(currentPage);
          } else {
            alert('Lỗi: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Có lỗi xảy ra khi cập nhật sản phẩm');
        });
      });
    }
    
    const loadDashboardStats = () => {
      fetch('/BloomWebsite/api/get-dashboard-stats.php')
        .then(response => response.json())
        .then(data => {
          document.querySelector('.stats-container .stat-card:nth-child(1) .stat-value').textContent = data.totalProducts || 0;
          document.querySelector('.stats-container .stat-card:nth-child(2) .stat-value').textContent = data.todayOrders || 0;
          document.querySelector('.stats-container .stat-card:nth-child(3) .stat-value').textContent = (data.monthRevenue / 1000).toFixed(1) + 'M đ' || '0';
          document.querySelector('.stats-container .stat-card:nth-child(4) .stat-value').textContent = data.newCustomers || 0;
          
          const recentOrdersTable = document.querySelector('#dashboard .data-table tbody');
          if (recentOrdersTable && data.recentOrders) {
            recentOrdersTable.innerHTML = '';
            
            data.recentOrders.forEach(order => {
              const tr = document.createElement('tr');
              tr.innerHTML = `
                <td>#ORD${order.id}</td>
                <td>${order.userName}</td>
                <td>${order.productName}</td>
                <td>${order.total.toLocaleString()} đ</td>
                <td>${order.status}</td>
                <td>${order.date}</td>
              `;
              recentOrdersTable.appendChild(tr);
            });
          }
        })
        .catch(error => {
          console.error('Error loading dashboard stats:', error);
        });
    };
    
    if (document.querySelector('#dashboard').classList.contains('active')) {
      loadDashboardStats();
    }
    
    menuItems.forEach(item => {
      item.addEventListener('click', function() {
        const sectionId = this.getAttribute('data-section');
        
        if (sectionId === 'dashboard') {
          loadDashboardStats();
        } else if (sectionId === 'product-list') {
          loadProducts(1);
        } else if (sectionId === 'user-list') {
          loadUsers();
        } else if (sectionId === 'order-list') {
          loadOrders('new');
        } else if (sectionId === 'order-processing') {
          loadOrders('processing');
        } else if (sectionId === 'order-history') {
          loadOrders('completed');
        } else if (sectionId === 'sales-report') {
          loadSalesReport('month');
        } else if (sectionId === 'product-categories') {
          loadCategories();
        }
      });
    });
  });

  let currentPage = 1;
  const itemsPerPage = 7;

  function loadProducts(page) {
      currentPage = page;
      fetch(`/BloomWebsite/api/get-products.php?page=${page}`)
          .then(res => res.json())
          .then(data => {
              const tbody = document.getElementById('product-body');
              if (!tbody) return;
              
              tbody.innerHTML = '';

              data.forEach(row => {
                  const tr = document.createElement('tr');
                  tr.innerHTML = `
                      <td>${row.id}</td>
                      <td><img src="${row.image_url || '/BloomWebsite/public/default-product.jpg'}" class="image-preview"></td>
                      <td>${row.name}</td>
                      <td>${(row.price * 1000).toLocaleString()}</td>
                      <td>${row.discount}%</td>
                      <td>${row.quantity}</td>
                      <td>
                        <div class="action-buttons">
                          <button class="action-btn edit-btn" data-id="${row.id}" data-name="${row.name}" data-price="${row.price}" data-discount="${row.discount}" data-quantity="${row.quantity}" data-type="${row.type}" data-image="${row.image_url}" data-description="${row.description || ''}"><i class="fa-solid fa-edit"></i></button>
                          <form method="POST" action="/BloomWebsite/composables/products/delete.php" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                            <input type="hidden" name="product_id" value="${row.id}">
                            <button type="submit" class="action-btn delete-btn"><i class="fa-solid fa-trash"></i></button>
                          </form>
                        </div>
                      </td>
                  `;
                  tbody.appendChild(tr);
              });
              
              fetchTotalProducts();
          })
          .catch(error => {
            console.error('Error loading products:', error);
          });
  }

  function fetchTotalProducts() {
      fetch(`/BloomWebsite/api/get-total-products.php`)
          .then(res => res.json())
          .then(data => {
              const totalPages = Math.ceil(data.total / itemsPerPage);
              renderPagination(totalPages);
          })
          .catch(error => {
            console.error('Error fetching total products:', error);
          });
  }

  function renderPagination(totalPages) {
      const container = document.getElementById('pagination');
      if (!container) return;
      
      container.innerHTML = '';
      
      if (currentPage > 1) {
          const prevBtn = document.createElement('button');
          prevBtn.innerHTML = '<i class="fa-solid fa-chevron-left"></i>';
          prevBtn.addEventListener('click', () => {
              loadProducts(currentPage - 1);
          });
          container.appendChild(prevBtn);
      }
      
      let startPage = Math.max(1, currentPage - 2);
      let endPage = Math.min(totalPages, startPage + 4);
      
      if (endPage - startPage < 4 && startPage > 1) {
          startPage = Math.max(1, endPage - 4);
      }
      
      for (let i = startPage; i <= endPage; i++) {
          const btn = document.createElement('button');
          btn.textContent = i;
          if (i === currentPage) btn.classList.add('active');
          
          btn.addEventListener('click', () => {
              loadProducts(i);
          });
          
          container.appendChild(btn);
      }
      
      if (currentPage < totalPages) {
          const nextBtn = document.createElement('button');
          nextBtn.innerHTML = '<i class="fa-solid fa-chevron-right"></i>';
          nextBtn.addEventListener('click', () => {
              loadProducts(currentPage + 1);
          });
          container.appendChild(nextBtn);
      }
  }
  
  const productSearchBox = document.querySelector('#product-list .search-box input');
  if (productSearchBox) {
      productSearchBox.addEventListener('keyup', function(e) {
          if (e.key === 'Enter') {
              const searchTerm = this.value.trim();
              if (searchTerm) {
                  fetch(`/BloomWebsite/api/search-products.php?q=${encodeURIComponent(searchTerm)}`)
                      .then(res => res.json())
                      .then(data => {
                          const tbody = document.getElementById('product-body');
                          tbody.innerHTML = '';
                          
                          data.forEach(row => {
                              const tr = document.createElement('tr');
                              tr.innerHTML = `
                                  <td>${row.id}</td>
                                  <td><img src="${row.image_url || '/BloomWebsite/public/default-product.jpg'}" class="image-preview"></td>
                                  <td>${row.name}</td>
                                  <td>${(row.price * 1000).toLocaleString()}</td>
                                  <td>${row.discount}%</td>
                                  <td>${row.quantity}</td>
                                  <td>
                                    <div class="action-buttons">
                                      <button class="action-btn edit-btn" data-id="${row.id}" data-name="${row.name}" data-price="${row.price}" data-discount="${row.discount}" data-quantity="${row.quantity}" data-type="${row.type}" data-image="${row.image_url}" data-description="${row.description || ''}"><i class="fa-solid fa-edit"></i></button>
                                      <form method="POST" action="/BloomWebsite/composables/products/delete.php" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                        <input type="hidden" name="product_id" value="${row.id}">
                                        <button type="submit" class="action-btn delete-btn"><i class="fa-solid fa-trash"></i></button>
                                      </form>
                                    </div>
                                  </td>
                              `;
                              tbody.appendChild(tr);
                          });
                          
                          document.getElementById('pagination').style.display = 'none';
                      });
              } else {
                  loadProducts(1);
                  document.getElementById('pagination').style.display = 'flex';
              }
          }
      });
  }
  
  const productFilterDropdown = document.querySelector('#product-list .filter-dropdown');
  if (productFilterDropdown) {
      productFilterDropdown.addEventListener('change', function() {
          const selectedType = this.value;
          
          if (selectedType) {
              fetch(`/BloomWebsite/api/filter-products.php?type=${encodeURIComponent(selectedType)}`)
                  .then(res => res.json())
                  .then(data => {
                      const tbody = document.getElementById('product-body');
                      tbody.innerHTML = '';
                      
                      data.forEach(row => {
                          const tr = document.createElement('tr');
                          tr.innerHTML = `
                              <td>${row.id}</td>
                              <td><img src="${row.image_url || '/BloomWebsite/public/default-product.jpg'}" class="image-preview"></td>
                              <td>${row.name}</td>
                              <td>${(row.price * 1000).toLocaleString()}</td>
                              <td>${row.discount}%</td>
                              <td>${row.quantity}</td>
                              <td>
                                <div class="action-buttons">
                                  <button class="action-btn edit-btn" data-id="${row.id}" data-name="${row.name}" data-price="${row.price}" data-discount="${row.discount}" data-quantity="${row.quantity}" data-type="${row.type}" data-image="${row.image_url}" data-description="${row.description || ''}"><i class="fa-solid fa-edit"></i></button>
                                  <form method="POST" action="/BloomWebsite/composables/products/delete.php" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                    <input type="hidden" name="product_id" value="${row.id}">
                                    <button type="submit" class="action-btn delete-btn"><i class="fa-solid fa-trash"></i></button>
                                  </form>
                                </div>
                              </td>
                          `;
                          tbody.appendChild(tr);
                      });
                      
                      document.getElementById('pagination').style.display = 'none';
                  });
          } else {
              loadProducts(1);
              document.getElementById('pagination').style.display = 'flex';
          }
      });
  }
  
  function loadUsers() {
      fetch(`/BloomWebsite/api/get-users.php`)
          .then(res => res.json())
          .then(data => {
              const tbody = document.querySelector('#user-list .data-table tbody');
              if (!tbody) return;
              
              tbody.innerHTML = '';
              
              data.forEach(user => {
                  const tr = document.createElement('tr');
                  tr.innerHTML = `
                      <td>${user.id}</td>
                      <td>${user.name}</td>
                      <td>${user.username}</td>
                      <td>${user.email}</td>
                      <td>${user.role || 'Khách hàng'}</td>
                      <td>
                          <div class="action-buttons">
                              <button class="action-btn edit-btn" data-id="${user.id}" data-name="${user.name}" data-username="${user.username}" data-email="${user.email}" data-role="${user.role}"><i class="fa-solid fa-edit"></i></button>
                              <form method="POST" action="/BloomWebsite/composables/users/delete.php" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">
                                  <input type="hidden" name="user_id" value="${user.id}">
                                  <button type="submit" class="action-btn delete-btn"><i class="fa-solid fa-trash"></i></button>
                              </form>
                          </div>
                      </td>
                  `;
                  tbody.appendChild(tr);
              });
          })
          .catch(error => {
              console.error('Error loading users:', error);
          });
  }
  
  const userSearchBox = document.querySelector('#user-list .search-box input');
  if (userSearchBox) {
      userSearchBox.addEventListener('keyup', function(e) {
          if (e.key === 'Enter') {
              const searchTerm = this.value.trim();
              
              if (searchTerm) {
                  fetch(`/BloomWebsite/api/search-users.php?q=${encodeURIComponent(searchTerm)}`)
                      .then(res => res.json())
                      .then(data => {
                          const tbody = document.querySelector('#user-list .data-table tbody');
                          tbody.innerHTML = '';
                          
                          data.forEach(user => {
                              const tr = document.createElement('tr');
                              tr.innerHTML = `
                                  <td>${user.id}</td>
                                  <td>${user.name}</td>
                                  <td>${user.username}</td>
                                  <td>${user.email}</td>
                                  <td>${user.role || 'Khách hàng'}</td>
                                  <td>
                                      <div class="action-buttons">
                                          <button class="action-btn edit-btn" data-id="${user.id}" data-name="${user.name}" data-username="${user.username}" data-email="${user.email}" data-role="${user.role}"><i class="fa-solid fa-edit"></i></button>
                                          <form method="POST" action="/BloomWebsite/composables/users/delete.php" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">
                                              <input type="hidden" name="user_id" value="${user.id}">
                                              <button type="submit" class="action-btn delete-btn"><i class="fa-solid fa-trash"></i></button>
                                          </form>
                                      </div>
                                  </td>
                              `;
                              tbody.appendChild(tr);
                          });
                      });
              } else {
                  loadUsers();
              }
          }
      });
  }
  
  document.addEventListener('DOMContentLoaded', () => {
      if (document.getElementById('product-list').classList.contains('active')) {
          loadProducts(1);
      }
      
      if (document.getElementById('user-list').classList.contains('active')) {
          loadUsers();
      }
  });