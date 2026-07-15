let storesCache = null;

function loadStores() {
  // If already cached, just populate and return a resolved promise.
  if (storesCache) {
    populateStoreDropdown(storesCache);
    return Promise.resolve(storesCache);
  }

  return $.ajax({
    url: baseUrl + "DistributionCategory/FetchAll",
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response && response.success) {
        storesCache = response.data; // cache the data
        populateStoreDropdown(storesCache);
      }
    },
    error: function (xhr) {
      console.error(
        "Error fetching stores:",
        xhr.responseJSON?.message ||
          xhr.responseJSON?.error ||
          "Failed to fetch stores.",
      );
    },
  });
}

function populateStoreDropdown(data) {
  const $select = $("#distributionGroupName");
  const currentVal = $select.val();

  $select.find("option").not(":first").remove();

  $.each(data, function (_, store) {
    $select.append(
      $("<option>", {
        value: store.dist_cat_id,
        text: store.name,
      }),
    );
  });

  $select.val(currentVal);
}
