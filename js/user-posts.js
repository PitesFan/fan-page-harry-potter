document.addEventListener("DOMContentLoaded", () => {
  const grid = document.querySelector("[data-user-posts-grid]");

  if (!grid) {
    return;
  }

  const isLoggedIn = grid.dataset.loggedIn === "1";
  const currentUser = grid.dataset.currentUser || "";
  const lang = window.userPostsLang || {};

  const firstPost = grid.querySelector(".post");
  const isDarkTheme = firstPost?.classList.contains("white-bg") ?? true;
  const postBgClass = isDarkTheme ? "white-bg" : "black-bg";
  const titleClass = isDarkTheme ? "black" : "white";
  const descriptionClass = isDarkTheme ? "dark-gray" : "light-gray";
  const metaClass = isDarkTheme ? "dark-gray" : "light-gray";
  const fallbackImage = "images/post-img-test.jpg";

  const renderPost = (post) => {
    const card = document.createElement("div");
    card.className = `post ${postBgClass}`;
    card.dataset.postId = post.id || "";

    const image = document.createElement("img");
    image.className = "post-img";
    image.src = post.cover || fallbackImage;
    image.alt = post.title || "User post";
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
    const author = post.author_username || post.author_email || "";
    meta.textContent = `${lang.by || "by"} ${author}`;

    card.append(image, title, description, meta);

    if (isLoggedIn && post.author_email === currentUser) {
      const actions = document.createElement("div");
      actions.className = "profile-buttons";

      const deleteBtn = document.createElement("button");
      deleteBtn.type = "button";
      deleteBtn.className = `button sign-up-btn accent-bg white`;
      deleteBtn.textContent = lang.delete || "Delete";
      deleteBtn.addEventListener("click", (event) => {
        event.preventDefault();
        event.stopPropagation();
        if (!confirm(lang.confirmDelete || "Delete this post?")) return;
        deletePost(post.id, card);
      });
      actions.appendChild(deleteBtn);

      card.appendChild(actions);
    }

    return card;
  };

  const showMessage = (text) => {
    grid.innerHTML = "";
    const empty = document.createElement("p");
    empty.className = isDarkTheme ? "light-gray" : "dark-gray";
    empty.textContent = text;
    grid.appendChild(empty);
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
        alert(lang.deleteFailed || "Could not delete post");
        return;
      }
      card.remove();
      if (!grid.querySelector(".post")) {
        showMessage(lang.empty || "No posts yet. Be the first to share!");
      }
    } catch (err) {
      alert(lang.deleteFailed || "Could not delete post");
    }
  };

  const loadPosts = async () => {
    try {
      const response = await fetch("php/list_posts.php", {
        cache: "no-store",
      });
      if (!response.ok) {
        throw new Error("Failed to fetch posts");
      }
      const data = await response.json();
      const posts = Array.isArray(data.posts) ? data.posts : [];

      if (!posts.length) {
        showMessage(lang.empty || "No posts yet. Be the first to share!");
        return;
      }

      grid.replaceChildren(...posts.map(renderPost));
    } catch (err) {
      showMessage(lang.error || "Could not load posts");
    }
  };

  loadPosts();
});
