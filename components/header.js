window.addEventListener("DOMContentLoaded", () => {
const header = document.querySelector('.header');

const headerContent = `
     <header class="header">
    <!-- navbar -->
    <nav class="navbar">
      <div class="logo"><img src="../../assets/images/logo.png"> MJL Foundation</div>
      <ul class="nav-links">
       <li><a href="../home/home.html">Home</a></li>
        <li><a href="../about/About.html">About</a></li>
        <li><a href="../projects/Project.html">Projects</a></li>
        <li><a href="../blog/Blog.html">Blog</a></li>
        <li><a href="../get-involved/GetInvolved.html">Get Involved</a></li>
        <li><a href="../contact/Contact.html">Contact</a></li>
      </ul>
      <a href="#" class="donate-btn"><i class="fas fa-heart"></i>  Donate</a>
    </nav>

</header>
`
header.innerHTML = headerContent;

});