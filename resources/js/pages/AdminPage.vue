<script setup>
import { computed, inject, onMounted, reactive, ref } from 'vue';
import { api } from '../services/api';
import ContentPreviewModal from '../components/ContentPreviewModal.vue';

const site = inject('site');
const tab = ref('projects');
const projectSectionTab = ref('list');
const postSectionTab = ref('list');
const seasonSectionTab = ref('list');
const categorySectionTab = ref('list');
const capabilitySectionTab = ref('list');
const message = ref('');
const error = ref('');
const projects = ref([]);
const posts = ref([]);
const courses = ref([]);
const seasons = ref([]);
const categories = ref([]);
const capabilities = ref([]);
const socialLinks = ref({});
const settings = ref({});
const credentials = reactive({
    email: '',
    password: '',
});

const showPreviewModal = ref(false);
const previewType = ref('project');
const previewLocale = ref('en');
const previewHtml = ref('');
const previewTitle = ref('');

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
    categories: [],
    capabilities: [],
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
    season_id: null,
    episode_number: null,
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

const blankSeason = () => ({
    id: null,
    slug: '',
    status: 'draft',
    sort_order: 0,
    name: { es: '', en: '' },
    description: { es: '', en: '' },
});

const blankCategory = () => ({
    id: null,
    slug: '',
    dimension: 'domain',
    name: { es: '', en: '' },
    description: { es: '', en: '' },
});

const blankCapability = () => ({
    id: null,
    slug: '',
    sort_order: 0,
    name: { es: '', en: '' },
    description: { es: '', en: '' },
});

const projectForm = reactive(blankProject());
const postForm = reactive(blankPost());
const courseForm = reactive(blankCourse());
const seasonForm = reactive(blankSeason());
const categoryForm = reactive(blankCategory());
const capabilityForm = reactive(blankCapability());
const stackJson = ref('[]');
const experienceJson = ref('[]');

const isAdmin = computed(() => Boolean(site.authUser.value?.is_admin));

const resetProjectForm = () => {
    Object.assign(projectForm, blankProject());
    projectSectionTab.value = 'form';
};
const resetPostForm = () => {
    Object.assign(postForm, blankPost());
    postSectionTab.value = 'form';
};
const resetCourseForm = () => Object.assign(courseForm, blankCourse());

const resetSeasonForm = () => {
    Object.assign(seasonForm, blankSeason());
    seasonSectionTab.value = 'form';
};

const resetCategoryForm = () => {
    Object.assign(categoryForm, blankCategory());
    categorySectionTab.value = 'form';
};

const resetCapabilityForm = () => {
    Object.assign(capabilityForm, blankCapability());
    capabilitySectionTab.value = 'form';
};

const fillProject = (project) => {
    Object.assign(projectForm, JSON.parse(JSON.stringify(project)));
    projectForm.stackInput = (project.stack || []).join(', ');
    projectSectionTab.value = 'form';
};

const fillPost = (post) => {
    Object.assign(postForm, JSON.parse(JSON.stringify(post)));
    postSectionTab.value = 'form';
};

const fillCourse = (course) => {
    Object.assign(courseForm, JSON.parse(JSON.stringify(course)));
    if (courseForm.issued_at) {
        courseForm.issued_at = courseForm.issued_at.substring(0, 10);
    }
};

const fillSeason = (season) => {
    Object.assign(seasonForm, JSON.parse(JSON.stringify(season)));
    seasonSectionTab.value = 'form';
};

const fillCategory = (category) => {
    Object.assign(categoryForm, JSON.parse(JSON.stringify(category)));
    categorySectionTab.value = 'form';
};

const fillCapability = (capability) => {
    Object.assign(capabilityForm, JSON.parse(JSON.stringify(capability)));
    capabilitySectionTab.value = 'form';
};

const loadAdminData = async () => {
    const [{ data: projectData }, { data: postData }, { data: courseData }, { data: socialData }, { data: settingsData }, { data: seasonData }, { data: categoryData }, { data: capabilityData }] = await Promise.all([
        api.get('/api/admin/projects'),
        api.get('/api/admin/posts'),
        api.get('/api/admin/courses'),
        api.get('/api/admin/social-links'),
        api.get('/api/admin/settings'),
        api.get('/api/admin/seasons'),
        api.get('/api/admin/categories'),
        api.get('/api/admin/capabilities'),
    ]);

    projects.value = projectData;
    posts.value = postData;
    courses.value = courseData;
    socialLinks.value = socialData;
    settings.value = settingsData;
    seasons.value = seasonData.data;
    categories.value = categoryData.data;
    capabilities.value = capabilityData.data;
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

    await loadAdminData();
    message.value = 'Project saved.';
    projectSectionTab.value = 'form';
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

    await loadAdminData();
    message.value = 'Post saved.';
    postSectionTab.value = 'form';
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

const saveSeason = async () => {
    if (seasonForm.id) {
        await api.put(`/api/admin/seasons/${seasonForm.id}`, seasonForm);
    } else {
        await api.post('/api/admin/seasons', seasonForm);
    }

    await loadAdminData();
    message.value = 'Season saved.';
    seasonSectionTab.value = 'form';
};

const deleteSeason = async (id) => {
    await api.delete(`/api/admin/seasons/${id}`);
    await loadAdminData();
};

const saveCategory = async () => {
    if (categoryForm.id) {
        await api.put(`/api/admin/categories/${categoryForm.id}`, categoryForm);
    } else {
        await api.post('/api/admin/categories', categoryForm);
    }

    await loadAdminData();
    message.value = 'Category saved.';
    categorySectionTab.value = 'form';
};

const deleteCategory = async (id) => {
    await api.delete(`/api/admin/categories/${id}`);
    await loadAdminData();
};

const saveCapability = async () => {
    if (capabilityForm.id) {
        await api.put(`/api/admin/capabilities/${capabilityForm.id}`, capabilityForm);
    } else {
        await api.post('/api/admin/capabilities', capabilityForm);
    }

    await loadAdminData();
    message.value = 'Capability saved.';
    capabilitySectionTab.value = 'form';
};

const deleteCapability = async (id) => {
    await api.delete(`/api/admin/capabilities/${id}`);
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

const openPreview = async (type) => {
    const formData = type === 'project' ? projectForm : postForm;
    previewType.value = type;
    previewLocale.value = 'en';
    previewTitle.value = formData.title?.en || formData.title?.es || '';

    try {
        const { data } = await api.post('/api/admin/preview', {
            type,
            locale: 'en',
            data: JSON.parse(JSON.stringify(formData)),
        });
        previewHtml.value = data.html;
        previewTitle.value = data.title;
        showPreviewModal.value = true;
    } catch {
        error.value = 'Failed to load preview.';
    }
};

const togglePreviewLocale = async () => {
    const newLocale = previewLocale.value === 'en' ? 'es' : 'en';
    const formData = previewType.value === 'project' ? projectForm : postForm;
    previewLocale.value = newLocale;

    try {
        const { data } = await api.post('/api/admin/preview', {
            type: previewType.value,
            locale: newLocale,
            data: JSON.parse(JSON.stringify(formData)),
        });
        previewHtml.value = data.html;
        previewTitle.value = data.title;
    } catch (e) {
        console.error('[Preview] Failed to toggle locale:', e?.response?.data ?? e);
        error.value = 'Failed to load preview.';
    }
};

const closePreview = () => {
    showPreviewModal.value = false;
    previewHtml.value = '';
};

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
                <button v-for="item in ['projects', 'posts', 'seasons', 'categories', 'capabilities', 'courses', 'settings', 'social']" :key="item" :class="{ active: tab === item }" @click="tab = item">
                    {{ item }}
                </button>
            </div>

            <p v-if="message" class="success-text">{{ message }}</p>
            <p v-if="error" class="error-text">{{ error }}</p>

            <section v-if="tab === 'projects'">
                <div v-if="projectSectionTab === 'list'" class="panel">
                    <div class="section-heading">
                        <h2>Projects</h2>
                        <button class="ghost-button" @click="resetProjectForm">New</button>
                    </div>
                    <div class="admin-list">
                        <button v-for="project in projects" :key="project.id" class="admin-list-item" @click="fillProject(project)">
                            <span class="star-indicator" :class="{ 'is-featured': project.featured }">★</span>
                            <strong>{{ project.title.en }}</strong>
                            <span>{{ project.status }}</span>
                        </button>
                    </div>
                </div>
                <form v-if="projectSectionTab === 'form'" class="panel editor-form" @submit.prevent="saveProject">
                    <div class="section-heading">
                        <button class="ghost-button" type="button" @click="projectSectionTab = 'list'">← Back</button>
                        <h2>{{ projectForm.id ? 'Edit project' : 'New project' }}</h2>
                    </div>
                    <div class="two-column">
                        <input v-model="projectForm.title.es" placeholder="Titulo ES">
                        <input v-model="projectForm.title.en" placeholder="Title EN">
                        <input v-model="projectForm.slug" placeholder="Slug">
                        <input v-model="projectForm.cover_image_url" placeholder="Cover image URL">
                        <input v-model="projectForm.demo_url" placeholder="Demo URL">
                        <input v-model="projectForm.repository_url" placeholder="Repository URL">
                        <input v-model="projectForm.published_at" type="datetime-local">
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
                    <div class="sub-editor">
                        <h3>Categories</h3>
                        <div class="checkbox-grid">
                            <label v-for="cat in categories" :key="cat.id">
                                <input type="checkbox" :value="cat.id" v-model="projectForm.categories">
                                {{ cat.name?.en || cat.slug }}
                            </label>
                        </div>
                    </div>
                    <div class="sub-editor">
                        <h3>Capabilities</h3>
                        <div class="checkbox-grid">
                            <label v-for="cap in capabilities" :key="cap.id">
                                <input type="checkbox" :value="cap.id" v-model="projectForm.capabilities">
                                {{ cap.name?.en || cap.slug }}
                            </label>
                        </div>
                    </div>
                    <div class="cta-row">
                        <button class="primary-button" type="submit">Save project</button>
                        <button class="secondary-button" type="button" @click="openPreview('project')">Preview</button>
                        <button v-if="projectForm.id" class="danger-button" type="button" @click="deleteProject(projectForm.id)">Delete</button>
                    </div>
                </form>
            </section>

            <section v-if="tab === 'posts'">
                <div v-if="postSectionTab === 'list'" class="panel">
                    <div class="section-heading">
                        <h2>Posts</h2>
                        <button class="ghost-button" @click="resetPostForm">New</button>
                    </div>
                    <div class="admin-list">
                        <button v-for="post in posts" :key="post.id" class="admin-list-item" @click="fillPost(post)">
                            <span class="star-indicator" :class="{ 'is-featured': post.featured }">★</span>
                            <strong>{{ post.title.en }}</strong>
                            <span>{{ post.type }}</span>
                            <span v-if="post.season" class="season-badge">{{ post.season.name?.en || post.season.slug }} #{{ post.episode_number }}</span>
                        </button>
                    </div>
                </div>
                <form v-if="postSectionTab === 'form'" class="panel editor-form" @submit.prevent="savePost">
                    <div class="section-heading">
                        <button class="ghost-button" type="button" @click="postSectionTab = 'list'">← Back</button>
                        <h2>{{ postForm.id ? 'Edit post' : 'New post' }}</h2>
                    </div>
                    <div class="two-column">
                        <input v-model="postForm.title.es" placeholder="Titulo ES">
                        <input v-model="postForm.title.en" placeholder="Title EN">
                        <input v-model="postForm.slug" placeholder="Slug">
                        <input v-model="postForm.cover_image_url" placeholder="Cover image URL">
                        <input v-model="postForm.external_url" placeholder="External URL">
                        <input v-model="postForm.published_at" type="datetime-local">
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
                        <select v-model="postForm.season_id">
                            <option :value="null">Sin temporada</option>
                            <option v-for="season in seasons" :key="season.id" :value="season.id">{{ season.name?.en || season.slug }}</option>
                        </select>
                        <input v-model.number="postForm.episode_number" type="number" min="1" placeholder="Episode number">
                    </div>
                    <div class="two-column">
                        <textarea v-model="postForm.excerpt.es" rows="3" placeholder="Extracto ES" />
                        <textarea v-model="postForm.excerpt.en" rows="3" placeholder="Excerpt EN" />
                        <textarea v-model="postForm.content.es" rows="8" placeholder="Contenido ES" />
                        <textarea v-model="postForm.content.en" rows="8" placeholder="Content EN" />
                    </div>
                    <div class="cta-row">
                        <button class="primary-button" type="submit">Save post</button>
                        <button class="secondary-button" type="button" @click="openPreview('post')">Preview</button>
                        <button v-if="postForm.id" class="danger-button" type="button" @click="deletePost(postForm.id)">Delete</button>
                    </div>
                </form>
            </section>

            <section v-if="tab === 'seasons'">
                <div v-if="seasonSectionTab === 'list'" class="panel">
                    <div class="section-heading">
                        <h2>Seasons</h2>
                        <button class="ghost-button" @click="resetSeasonForm">New</button>
                    </div>
                    <div class="admin-list">
                        <button v-for="season in seasons" :key="season.id" class="admin-list-item" @click="fillSeason(season)">
                            <strong>{{ season.name?.en || season.slug }}</strong>
                            <span>{{ season.status }}</span>
                        </button>
                    </div>
                </div>
                <form v-if="seasonSectionTab === 'form'" class="panel editor-form" @submit.prevent="saveSeason">
                    <div class="section-heading">
                        <button class="ghost-button" type="button" @click="seasonSectionTab = 'list'">← Back</button>
                        <h2>{{ seasonForm.id ? 'Edit season' : 'New season' }}</h2>
                    </div>
                    <div class="two-column">
                        <input v-model="seasonForm.name.es" placeholder="Nombre ES">
                        <input v-model="seasonForm.name.en" placeholder="Name EN">
                        <input v-model="seasonForm.slug" placeholder="Slug">
                        <input v-model.number="seasonForm.sort_order" type="number" min="0" placeholder="Sort order">
                    </div>
                    <div class="editor-toggles">
                        <select v-model="seasonForm.status">
                            <option value="draft">Draft</option>
                            <option value="upcoming">Upcoming</option>
                            <option value="active">Active</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="two-column">
                        <textarea v-model="seasonForm.description.es" rows="3" placeholder="Descripcion ES" />
                        <textarea v-model="seasonForm.description.en" rows="3" placeholder="Description EN" />
                    </div>
                    <div class="cta-row">
                        <button class="primary-button" type="submit">Save season</button>
                        <button v-if="seasonForm.id" class="danger-button" type="button" @click="deleteSeason(seasonForm.id)">Delete</button>
                    </div>
                </form>
            </section>

            <section v-if="tab === 'categories'">
                <div v-if="categorySectionTab === 'list'" class="panel">
                    <div class="section-heading">
                        <h2>Categories</h2>
                        <button class="ghost-button" @click="resetCategoryForm">New</button>
                    </div>
                    <div class="admin-list">
                        <button v-for="category in categories" :key="category.id" class="admin-list-item" @click="fillCategory(category)">
                            <strong>{{ category.name?.en || category.slug }}</strong>
                            <span>{{ category.dimension }}</span>
                        </button>
                    </div>
                </div>
                <form v-if="categorySectionTab === 'form'" class="panel editor-form" @submit.prevent="saveCategory">
                    <div class="section-heading">
                        <button class="ghost-button" type="button" @click="categorySectionTab = 'list'">← Back</button>
                        <h2>{{ categoryForm.id ? 'Edit category' : 'New category' }}</h2>
                    </div>
                    <div class="two-column">
                        <input v-model="categoryForm.name.es" placeholder="Nombre ES">
                        <input v-model="categoryForm.name.en" placeholder="Name EN">
                        <input v-model="categoryForm.slug" placeholder="Slug">
                        <select v-model="categoryForm.dimension">
                            <option value="domain">Domain</option>
                            <option value="capability">Capability</option>
                            <option value="technology">Technology</option>
                            <option value="methodology">Methodology</option>
                        </select>
                    </div>
                    <div class="two-column">
                        <textarea v-model="categoryForm.description.es" rows="3" placeholder="Descripcion ES" />
                        <textarea v-model="categoryForm.description.en" rows="3" placeholder="Description EN" />
                    </div>
                    <div class="cta-row">
                        <button class="primary-button" type="submit">Save category</button>
                        <button v-if="categoryForm.id" class="danger-button" type="button" @click="deleteCategory(categoryForm.id)">Delete</button>
                    </div>
                </form>
            </section>

            <section v-if="tab === 'capabilities'">
                <div v-if="capabilitySectionTab === 'list'" class="panel">
                    <div class="section-heading">
                        <h2>Capabilities</h2>
                        <button class="ghost-button" @click="resetCapabilityForm">New</button>
                    </div>
                    <div class="admin-list">
                        <button v-for="capability in capabilities" :key="capability.id" class="admin-list-item" @click="fillCapability(capability)">
                            <strong>{{ capability.name?.en || capability.slug }}</strong>
                        </button>
                    </div>
                </div>
                <form v-if="capabilitySectionTab === 'form'" class="panel editor-form" @submit.prevent="saveCapability">
                    <div class="section-heading">
                        <button class="ghost-button" type="button" @click="capabilitySectionTab = 'list'">← Back</button>
                        <h2>{{ capabilityForm.id ? 'Edit capability' : 'New capability' }}</h2>
                    </div>
                    <div class="two-column">
                        <input v-model="capabilityForm.name.es" placeholder="Nombre ES">
                        <input v-model="capabilityForm.name.en" placeholder="Name EN">
                        <input v-model="capabilityForm.slug" placeholder="Slug">
                        <input v-model.number="capabilityForm.sort_order" type="number" min="0" placeholder="Sort order">
                    </div>
                    <div class="two-column">
                        <textarea v-model="capabilityForm.description.es" rows="3" placeholder="Descripcion ES" />
                        <textarea v-model="capabilityForm.description.en" rows="3" placeholder="Description EN" />
                    </div>
                    <div class="cta-row">
                        <button class="primary-button" type="submit">Save capability</button>
                        <button v-if="capabilityForm.id" class="danger-button" type="button" @click="deleteCapability(capabilityForm.id)">Delete</button>
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
                    <label class="checkbox-row">
                        <input v-model="settings.welcome_modal_enabled" type="checkbox">
                        Welcome modal enabled
                    </label>
                    <input v-model="settings.welcome_modal_video_url" placeholder="Welcome video URL (YouTube embed)">
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

        <ContentPreviewModal
            :show="showPreviewModal"
            :html="previewHtml"
            :title="previewTitle"
            :locale="previewLocale"
            @close="closePreview"
            @toggle-locale="togglePreviewLocale"
        />
    </div>
</template>
