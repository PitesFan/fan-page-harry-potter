document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("[data-create-post-form]");
  const grid = document.querySelector("[data-user-posts-grid]");

  if (!form) {
    return;
  }

  const isDarkTheme = document.body.classList.contains("black-bg");
  const postBgClass = isDarkTheme ? "white-bg" : "black-bg";
  const titleClass = isDarkTheme ? "black" : "white";
  const descriptionClass = isDarkTheme ? "dark-gray" : "light-gray";
  const metaClass = isDarkTheme ? "dark-gray" : "light-gray";
  const textColor = isDarkTheme ? "#f8f8f8" : "#0d0000";

  const lang = window.userPostsLang || {};
  const fallbackImage = "images/post-img-test.jpg";

  const feedback = form.querySelector("[data-post-feedback]");
  const submitBtn = form.querySelector("button[type='submit']");
  const coverInput = form.querySelector("[name='cover']");
  const fileLabel = form.querySelector("[data-file-label]");

  const setFeedback = (message, type) => {
    if (!feedback) return;
    feedback.textContent = message || "";
    feedback.classList.remove("slytherin", "accent");
    if (type === "success") {
      feedback.classList.add("slytherin");
    } else if (type === "error") {
      feedback.classList.add("accent");
    }
  };

  if (coverInput && fileLabel) {
    coverInput.addEventListener("change", () => {
      const file = coverInput.files && coverInput.files[0];
      fileLabel.textContent = file
        ? file.name
        : lang.chooseFile || "Choose a cover image";
    });
  }

  const renderPost = (post) => {
    const card = document.createElement("div");
    card.className = `post ${postBgClass}`;
    card.dataset.postId = post.id || "";

    const image = document.createElement("img");
    image.className = "post-img";
    image.src = post.cover || fallbackImage;
    image.alt = post.title || "";
    image.onerror = () => {
      image.src = fallbackImage;
    };

    const title = document.createElement("h4");
    title.className = titleClass;
    title.textContent = post.title || "";

    const description = document.createElement("p");
    description.className = descriptionClass;
    description.textContent = post.description || "";

    const meta = document.createElement("p");
    meta.className = metaClass;
    meta.textContent = `${lang.by || "by"} ${post.author_username || post.author_email || ""}`;

    const actions = document.createElement("div");
    actions.className = "profile-buttons";

    const deleteBtn = document.createElement("button");
    deleteBtn.type = "button";
    deleteBtn.className = `button sign-up-btn accent-bg white`;
    deleteBtn.textContent = lang.delete || "Delete";
    deleteBtn.addEventListener("click", (event) => {
      event.preventDefault();
      if (!confirm(lang.confirmDelete || "Delete this post?")) return;
      deletePost(post.id, card);
    });
    actions.appendChild(deleteBtn);

    card.append(image, title, description, meta, actions);
    return card;
  };

  const deletePost = async (id, card) => {
    try {
      const response = await fetch("php/delete_post.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id }),
      });
      const data = await response.json();
      if (!data.ok) {
        setFeedback(lang.deleteFailed || "Could not delete post", "error");
        return;
      }
      card.remove();
      if (grid && !grid.querySelector(".post")) {
        showMessage(grid, lang.empty || "You haven't shared any posts yet.");
      }
    } catch (err) {
      setFeedback(lang.deleteFailed || "Could not delete post", "error");
    }
  };

  const showMessage = (target, text) => {
    if (!target) return;
    target.innerHTML = "";
    const empty = document.createElement("p");
    empty.className = isDarkTheme ? "light-gray" : "dark-gray";
    empty.textContent = text;
    target.appendChild(empty);
  };

  const loadPosts = async () => {
    if (!grid) return;
    try {
      const response = await fetch("php/list_my_posts.php", {
        cache: "no-store",
      });
      if (!response.ok) throw new Error("Failed to load");
      const data = await response.json();
      const posts = Array.isArray(data.posts) ? data.posts : [];
      if (!posts.length) {
        showMessage(grid, lang.empty || "You haven't shared any posts yet.");
        return;
      }
      grid.replaceChildren(...posts.map(renderPost));
    } catch (err) {
      showMessage(grid, lang.error || "Could not load posts");
    }
  };

  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    setFeedback("", null);

    const formData = new FormData(form);
    if (submitBtn) submitBtn.disabled = true;

    try {
      const response = await fetch("php/create_post.php", {
        method: "POST",
        body: formData,
      });
      const data = await response.json();

      if (!data.ok) {
        const key = data.error || "generic";
        setFeedback(
          lang.errors?.[key] || lang.errors?.generic || "Something went wrong",
          "error",
        );
        return;
      }

      setFeedback(lang.success || "Post created!", "success");
      form.reset();
      if (fileLabel)
        fileLabel.textContent = lang.chooseFile || "Choose a cover image";

      if (grid) {
        grid.prepend(renderPost(data.post));
      }
    } catch (err) {
      setFeedback(lang.errors?.generic || "Something went wrong", "error");
    } finally {
      if (submitBtn) submitBtn.disabled = false;
    }
  });

  loadPosts();

  const style = document.createElement("style");
  style.textContent = `
    [data-create-post-form] input,
    [data-create-post-form] textarea {
      color: ${textColor};
    }
    [data-create-post-form] input::placeholder,
    [data-create-post-form] textarea::placeholder {
      color: ${textColor};
      opacity: 0.6;
    }
  `;
  document.head.appendChild(style);
});
