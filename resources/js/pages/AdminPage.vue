<script setup>
import { computed, inject, onMounted, reactive, ref } from 'vue';
import { api } from '../services/api';

const site = inject('site');
const tab = ref('projects');
const message = ref('');
const error = ref('');
const projects = ref([]);
const posts = ref([]);
const courses = ref([]);
const socialLinks = ref([]);
const settings = ref({});
const credentials = reactive({
    email: '',
    password: '',
});

const blankProject = () => ({
    id: null,
    slug: '',
    status: 'draft',
    featured: false,
    sort_order: 0,
    cover_image_url: '',
    demo_url: '',
    repository_url: '',
    title: { es: '', en: '' },
    summary: { es: '', en: '' },
    description: { es: '', en: '' },
    details: [],
    stack: [],
    stackInput: '',
    media: [],
    published_at: '',
});

const blankPost = () => ({
    id: null,
    type: 'internal',
    slug: '',
    status: 'draft',
    featured: false,
    cover_image_url: '',
    external_url: '',
    share_enabled: true,
    title: { es: '', en: '' },
    excerpt: { es: '', en: '' },
    content: { es: '', en: '' },
    published_at: '',
});

const blankCourse = () => ({
    id: null,
    slug: '',
    status: 'draft',
    featured: false,
    sort_order: 0,
    name: { es: '', en: '' },
    issuer: '',
    issued_at: '',
    credential_id: '',
    url: '',
});

const projectForm = reactive(blankProject());
const postForm = reactive(blankPost());
const courseForm = reactive(blankCourse());
const stackJson = ref('[]');
const experienceJson = ref('[]');

const isAdmin = computed(() => Boolean(site.authUser.value?.is_admin));

const resetProjectForm = () => Object.assign(projectForm, blankProject());
const resetPostForm = () => Object.assign(postForm, blankPost());
const resetCourseForm = () => Object.assign(courseForm, blankCourse());

const fillProject = (project) => {
    Object.assign(projectForm, JSON.parse(JSON.stringify(project)));
    projectForm.stackInput = (project.stack || []).join(', ');
};

const fillPost = (post) => {
    Object.assign(postForm, JSON.parse(JSON.stringify(post)));
    if (postForm.published_at) {
        postForm.published_at = postForm.published_at.substring(0, 10);
    }
};

const fillCourse = (course) => {
    Object.assign(courseForm, JSON.parse(JSON.stringify(course)));
};

const loadAdminData = async () => {
    const [{ data: projectData }, { data: postData }, { data: courseData }, { data: socialData }, { data: settingsData }] = await Promise.all([
        api.get('/api/admin/projects'),
        api.get('/api/admin/posts'),
        api.get('/api/admin/courses'),
        api.get('/api/admin/social-links'),
        api.get('/api/admin/settings'),
    ]);

    projects.value = projectData;
    posts.value = postData;
    courses.value = courseData;
    socialLinks.value = socialData;
    settings.value = settingsData;
    stackJson.value = JSON.stringify(settingsData.stack || [], null, 2);
    experienceJson.value = JSON.stringify(settingsData.experience || [], null, 2);
};

const login = async () => {
    error.value = '';
    await api.get('/sanctum/csrf-cookie').catch(() => {});
    try {
        await api.post('/api/auth/login', credentials);
        await site.refreshAuth();
        await loadAdminData();
    } catch {
        error.value = 'Could not sign in.';
    }
};

const logout = async () => {
    await api.post('/api/auth/logout');
    await site.refreshAuth();
};

const saveProject = async () => {
    message.value = '';
    projectForm.stack = projectForm.stackInput.split(',').map((item) => item.trim()).filter(Boolean);
    const payload = { ...projectForm };
    delete payload.stackInput;

    if (projectForm.id) {
        await api.put(`/api/admin/projects/${projectForm.id}`, payload);
    } else {
        await api.post('/api/admin/projects', payload);
    }

    resetProjectForm();
    await loadAdminData();
    message.value = 'Project saved.';
};

const deleteProject = async (id) => {
    await api.delete(`/api/admin/projects/${id}`);
    await loadAdminData();
};

const savePost = async () => {
    if (postForm.id) {
        await api.put(`/api/admin/posts/${postForm.id}`, postForm);
    } else {
        await api.post('/api/admin/posts', postForm);
    }

    resetPostForm();
    await loadAdminData();
    message.value = 'Post saved.';
};

const deletePost = async (id) => {
    await api.delete(`/api/admin/posts/${id}`);
    await loadAdminData();
};

const saveCourse = async () => {
    if (courseForm.id) {
        await api.put(`/api/admin/courses/${courseForm.id}`, courseForm);
    } else {
        await api.post('/api/admin/courses', courseForm);
    }

    resetCourseForm();
    await loadAdminData();
    message.value = 'Course saved.';
};

const deleteCourse = async (id) => {
    await api.delete(`/api/admin/courses/${id}`);
    await loadAdminData();
};

const saveSocialLinks = async () => {
    await api.put('/api/admin/social-links', { links: socialLinks.value });
    await loadAdminData();
    message.value = 'Social links updated.';
};

const saveSettings = async () => {
    error.value = '';
    try {
        const payload = {
            ...settings.value,
            stack: JSON.parse(stackJson.value),
            experience: JSON.parse(experienceJson.value),
        };
        await api.put('/api/admin/settings', { settings: payload });
        await loadAdminData();
        await site.loadShell();
        message.value = 'Settings updated.';
    } catch {
        error.value = 'Stack or experience JSON is invalid.';
    }
};

const addDetail = () => projectForm.details.push({ label: { es: '', en: '' }, value: { es: '', en: '' } });
const addMedia = () => projectForm.media.push({ kind: 'image', url: '', caption: { es: '', en: '' }, sort_order: projectForm.media.length });
const addSocial = () => socialLinks.value.push({ platform: '', label: { es: '', en: '' }, url: '', icon: '', sort_order: socialLinks.value.length, active: true });

onMounted(async () => {
    await site.refreshAuth();
    if (isAdmin.value) {
        await loadAdminData();
    }
});
</script>

<template>
    <div class="admin-shell">
        <div v-if="!isAdmin" class="admin-login panel">
            <div class="section-heading">
                <h1>Admin</h1>
            </div>
            <form class="admin-form" @submit.prevent="login">
                <input v-model="credentials.email" type="email" placeholder="Email">
                <input v-model="credentials.password" type="password" placeholder="Password">
                <button class="primary-button" type="submit">Sign in</button>
            </form>
            <p v-if="error" class="error-text">{{ error }}</p>
        </div>

        <template v-else>
            <header class="admin-header">
                <div>
                    <p class="eyebrow">Internal editor</p>
                    <h1>Portfolio CMS</h1>
                </div>
                <div class="toolbar">
                    <a class="secondary-button" href="/" target="_blank" rel="noreferrer">Open site</a>
                    <button class="ghost-button" @click="logout">Logout</button>
                </div>
            </header>

            <div class="admin-tabs">
                <button v-for="item in ['projects', 'posts', 'courses', 'settings', 'social']" :key="item" :class="{ active: tab === item }" @click="tab = item">
                    {{ item }}
                </button>
            </div>

            <p v-if="message" class="success-text">{{ message }}</p>
            <p v-if="error" class="error-text">{{ error }}</p>

            <section v-if="tab === 'projects'" class="admin-grid">
                <div class="panel">
                    <div class="section-heading">
                        <h2>Projects</h2>
                        <button class="ghost-button" @click="resetProjectForm">New</button>
                    </div>
                    <div class="admin-list">
                        <button v-for="project in projects" :key="project.id" class="admin-list-item" @click="fillProject(project)">
                            <strong>{{ project.title.en }}</strong>
                            <span>{{ project.status }}</span>
                        </button>
                    </div>
                </div>
                <form class="panel editor-form" @submit.prevent="saveProject">
                    <h2>{{ projectForm.id ? 'Edit project' : 'New project' }}</h2>
                    <div class="two-column">
                        <input v-model="projectForm.title.es" placeholder="Titulo ES">
                        <input v-model="projectForm.title.en" placeholder="Title EN">
                        <input v-model="projectForm.slug" placeholder="Slug">
                        <input v-model="projectForm.cover_image_url" placeholder="Cover image URL">
                        <input v-model="projectForm.demo_url" placeholder="Demo URL">
                        <input v-model="projectForm.repository_url" placeholder="Repository URL">
                        <input v-model="projectForm.published_at" type="date">
                        <input v-model="projectForm.stackInput" placeholder="Stack separated by commas">
                    </div>
                    <div class="two-column">
                        <textarea v-model="projectForm.summary.es" rows="3" placeholder="Resumen ES" />
                        <textarea v-model="projectForm.summary.en" rows="3" placeholder="Summary EN" />
                        <textarea v-model="projectForm.description.es" rows="5" placeholder="Descripcion ES" />
                        <textarea v-model="projectForm.description.en" rows="5" placeholder="Description EN" />
                    </div>
                    <div class="editor-toggles">
                        <label><input v-model="projectForm.featured" type="checkbox"> Featured</label>
                        <select v-model="projectForm.status">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>
                    <div class="sub-editor">
                        <div class="section-heading">
                            <h3>Details</h3>
                            <button class="ghost-button" type="button" @click="addDetail">Add</button>
                        </div>
                        <div v-for="(detail, index) in projectForm.details" :key="index" class="repeat-row">
                            <input v-model="detail.label.es" placeholder="Label ES">
                            <input v-model="detail.label.en" placeholder="Label EN">
                            <input v-model="detail.value.es" placeholder="Value ES">
                            <input v-model="detail.value.en" placeholder="Value EN">
                        </div>
                    </div>
                    <div class="sub-editor">
                        <div class="section-heading">
                            <h3>Media</h3>
                            <button class="ghost-button" type="button" @click="addMedia">Add</button>
                        </div>
                        <div v-for="(item, index) in projectForm.media" :key="index" class="repeat-row">
                            <select v-model="item.kind">
                                <option value="image">Image</option>
                                <option value="video">Video</option>
                            </select>
                            <input v-model="item.url" placeholder="Media URL">
                            <input v-model="item.caption.es" placeholder="Caption ES">
                            <input v-model="item.caption.en" placeholder="Caption EN">
                        </div>
                    </div>
                    <div class="cta-row">
                        <button class="primary-button" type="submit">Save project</button>
                        <button v-if="projectForm.id" class="danger-button" type="button" @click="deleteProject(projectForm.id)">Delete</button>
                    </div>
                </form>
            </section>

            <section v-if="tab === 'posts'" class="admin-grid">
                <div class="panel">
                    <div class="section-heading">
                        <h2>Posts</h2>
                        <button class="ghost-button" @click="resetPostForm">New</button>
                    </div>
                    <div class="admin-list">
                        <button v-for="post in posts" :key="post.id" class="admin-list-item" @click="fillPost(post)">
                            <strong>{{ post.title.en }}</strong>
                            <span>{{ post.type }}</span>
                        </button>
                    </div>
                </div>
                <form class="panel editor-form" @submit.prevent="savePost">
                    <h2>{{ postForm.id ? 'Edit post' : 'New post' }}</h2>
                    <div class="two-column">
                        <input v-model="postForm.title.es" placeholder="Titulo ES">
                        <input v-model="postForm.title.en" placeholder="Title EN">
                        <input v-model="postForm.slug" placeholder="Slug">
                        <input v-model="postForm.cover_image_url" placeholder="Cover image URL">
                        <input v-model="postForm.external_url" placeholder="External URL">
                        <input v-model="postForm.published_at" type="date">
                    </div>
                    <div class="editor-toggles">
                        <select v-model="postForm.type">
                            <option value="internal">Internal</option>
                            <option value="external">External</option>
                        </select>
                        <select v-model="postForm.status">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                        <label><input v-model="postForm.featured" type="checkbox"> Featured</label>
                        <label><input v-model="postForm.share_enabled" type="checkbox"> Share enabled</label>
                    </div>
                    <div class="two-column">
                        <textarea v-model="postForm.excerpt.es" rows="3" placeholder="Extracto ES" />
                        <textarea v-model="postForm.excerpt.en" rows="3" placeholder="Excerpt EN" />
                        <textarea v-model="postForm.content.es" rows="8" placeholder="Contenido ES" />
                        <textarea v-model="postForm.content.en" rows="8" placeholder="Content EN" />
                    </div>
                    <div class="cta-row">
                        <button class="primary-button" type="submit">Save post</button>
                        <button v-if="postForm.id" class="danger-button" type="button" @click="deletePost(postForm.id)">Delete</button>
                    </div>
                </form>
            </section>

            <section v-if="tab === 'courses'" class="admin-grid">
                <div class="panel">
                    <div class="section-heading">
                        <h2>Courses</h2>
                        <button class="ghost-button" @click="resetCourseForm">New</button>
                    </div>
                    <div class="admin-list">
                        <button v-for="course in courses" :key="course.id" class="admin-list-item" @click="fillCourse(course)">
                            <strong>{{ course.name.en }}</strong>
                            <span>{{ course.status }}</span>
                        </button>
                    </div>
                </div>
                <form class="panel editor-form" @submit.prevent="saveCourse">
                    <h2>{{ courseForm.id ? 'Edit course' : 'New course' }}</h2>
                    <div class="two-column">
                        <input v-model="courseForm.name.es" placeholder="Nombre ES">
                        <input v-model="courseForm.name.en" placeholder="Name EN">
                        <input v-model="courseForm.slug" placeholder="Slug">
                        <input v-model="courseForm.issuer" placeholder="Issuer">
                        <input v-model="courseForm.issued_at" type="date">
                        <input v-model="courseForm.credential_id" placeholder="Credential ID (optional)">
                        <input v-model="courseForm.url" placeholder="Credential URL">
                    </div>
                    <div class="editor-toggles">
                        <label><input v-model="courseForm.featured" type="checkbox"> Featured</label>
                        <select v-model="courseForm.status">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>
                    <div class="cta-row">
                        <button class="primary-button" type="submit">Save course</button>
                        <button v-if="courseForm.id" class="danger-button" type="button" @click="deleteCourse(courseForm.id)">Delete</button>
                    </div>
                </form>
            </section>

            <section v-if="tab === 'settings'" class="panel editor-form">
                <h2>Site settings</h2>
                <div class="two-column">
                    <input v-model="settings.home.brand" placeholder="Brand">
                    <input v-model="settings.home.profileImage" placeholder="Profile image URL">
                    <input v-model="settings.home.headline.es" placeholder="Headline ES">
                    <input v-model="settings.home.headline.en" placeholder="Headline EN">
                    <textarea v-model="settings.home.bio.es" rows="4" placeholder="Bio ES" />
                    <textarea v-model="settings.home.bio.en" rows="4" placeholder="Bio EN" />
                    <input v-model="settings.contact.title.es" placeholder="Contact title ES">
                    <input v-model="settings.contact.title.en" placeholder="Contact title EN">
                    <textarea v-model="settings.contact.subtitle.es" rows="3" placeholder="Contact subtitle ES" />
                    <textarea v-model="settings.contact.subtitle.en" rows="3" placeholder="Contact subtitle EN" />
                    <input v-model="settings.contact.email" placeholder="Contact email">
                    <input v-model="settings.footer.copyright.es" placeholder="Footer ES">
                    <input v-model="settings.footer.copyright.en" placeholder="Footer EN">
                </div>
                <label>Stack JSON</label>
                <textarea v-model="stackJson" rows="12" />
                <label>Experience JSON</label>
                <textarea v-model="experienceJson" rows="12" />
                <button class="primary-button" type="button" @click="saveSettings">Save settings</button>
            </section>

            <section v-if="tab === 'social'" class="panel editor-form">
                <div class="section-heading">
                    <h2>Social links</h2>
                    <button class="ghost-button" type="button" @click="addSocial">Add</button>
                </div>
                <div v-for="(item, index) in socialLinks" :key="index" class="repeat-row">
                    <input v-model="item.platform" placeholder="Platform">
                    <input v-model="item.icon" placeholder="Icon">
                    <input v-model="item.label.es" placeholder="Label ES">
                    <input v-model="item.label.en" placeholder="Label EN">
                    <input v-model="item.url" placeholder="URL">
                    <label><input v-model="item.active" type="checkbox"> Active</label>
                </div>
                <button class="primary-button" type="button" @click="saveSocialLinks">Save social links</button>
            </section>
        </template>
    </div>
</template>
