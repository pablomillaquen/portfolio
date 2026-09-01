<script setup>
import { computed, inject, onMounted, reactive, ref } from 'vue';
import { api } from '../services/api';
import ContentPreviewModal from '../components/ContentPreviewModal.vue';
import ImageUploader from '../components/ImageUploader.vue';

const site = inject('site');
const tab = ref('projects');
const tabItems = [
    { key: 'projects', label: 'Proyectos' },
    { key: 'posts', label: 'Publicaciones' },
    { key: 'seasons', label: 'Temporadas' },
    { key: 'categories', label: 'Categorías' },
    { key: 'capabilities', label: 'Capacidades' },
    { key: 'courses', label: 'Cursos' },
    { key: 'settings', label: 'Configuración' },
    { key: 'social', label: 'Redes sociales' },
];
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

const postsBySeason = computed(() => {
    const withSeason = [];
    const noSeason = [];

    for (const post of posts.value) {
        if (post.season) {
            withSeason.push(post);
        } else {
            noSeason.push(post);
        }
    }

    const grouped = {};
    for (const post of withSeason) {
        const key = post.season.id;
        if (!grouped[key]) {
            grouped[key] = {
                season: post.season,
                posts: [],
            };
        }
        grouped[key].posts.push(post);
    }

    const result = Object.values(grouped).sort((a, b) => {
        const orderA = a.season.sort_order ?? 0;
        const orderB = b.season.sort_order ?? 0;
        return orderA - orderB;
    });

    for (const group of result) {
        group.posts.sort((a, b) => (a.episode_number ?? 0) - (b.episode_number ?? 0));
    }

    noSeason.sort((a, b) => b.id - a.id);

    return { groups: result, ungrouped: noSeason };
});

const categoriesByDimension = computed(() => {
    const dimensionLabels = {
        domain: 'Dominio',
        capability: 'Capacidad',
        technology: 'Tecnología',
        methodology: 'Metodología',
    };

    const grouped = {};
    for (const cat of categories.value) {
        const dim = cat.dimension || 'other';
        if (!grouped[dim]) {
            grouped[dim] = {
                label: dimensionLabels[dim] || dim,
                items: [],
            };
        }
        grouped[dim].items.push(cat);
    }

    const order = ['domain', 'capability', 'technology', 'methodology', 'other'];
    return order.filter(k => grouped[k]).map(k => grouped[k]);
});
const credentials = reactive({
    email: '',
    password: '',
});

const showPreviewModal = ref(false);
const previewType = ref('project');
const previewLocale = ref('es');
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
    problem: { es: '', en: '' },
    approach: { es: '', en: '' },
    contribution: { es: '', en: '' },
    what_it_demonstrates: { es: '', en: '' },
    details: [],
    stack: [],
    stackInput: '',
    media: [],
    published_at: '',
    categories: [],
    capabilities: [],
    posts: [],
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
    categories: [],
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

const formatDatetimeLocal = (value) => {
    if (!value) return '';
    const d = new Date(value);
    if (isNaN(d.getTime())) return '';
    const yyyy = d.getFullYear();
    const mm = String(d.getMonth() + 1).padStart(2, '0');
    const dd = String(d.getDate()).padStart(2, '0');
    const hh = String(d.getHours()).padStart(2, '0');
    const mi = String(d.getMinutes()).padStart(2, '0');
    return `${yyyy}-${mm}-${dd}T${hh}:${mi}`;
};

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
    const data = JSON.parse(JSON.stringify(project));
    data.title = data.title || { es: '', en: '' };
    data.summary = data.summary || { es: '', en: '' };
    data.description = data.description || { es: '', en: '' };
    data.problem = data.problem || { es: '', en: '' };
    data.approach = data.approach || { es: '', en: '' };
    data.contribution = data.contribution || { es: '', en: '' };
    data.what_it_demonstrates = data.what_it_demonstrates || { es: '', en: '' };
    data.details = data.details || [];
    data.stack = data.stack || [];
    data.media = data.media || [];
    data.categories = (data.categories || []).map(c => typeof c === 'object' ? c.id : c);
    data.capabilities = (data.capabilities || []).map(c => typeof c === 'object' ? c.id : c);
    data.posts = (data.posts || []).map(p => typeof p === 'object' ? p.id : p);
    data.published_at = formatDatetimeLocal(data.published_at);
    Object.assign(projectForm, data);
    projectForm.stackInput = (project.stack || []).join(', ');
    projectSectionTab.value = 'form';
};

const fillPost = (post) => {
    const data = JSON.parse(JSON.stringify(post));
    data.title = data.title || { es: '', en: '' };
    data.excerpt = data.excerpt || { es: '', en: '' };
    data.content = data.content || { es: '', en: '' };
    data.published_at = formatDatetimeLocal(data.published_at);
    Object.assign(postForm, data);
    postSectionTab.value = 'form';
};

const fillCourse = (course) => {
    Object.assign(courseForm, JSON.parse(JSON.stringify(course)));
    if (courseForm.issued_at) {
        courseForm.issued_at = courseForm.issued_at.substring(0, 10);
    }
};

const fillSeason = (season) => {
    const data = JSON.parse(JSON.stringify(season));
    data.name = data.name || { es: '', en: '' };
    data.description = data.description || { es: '', en: '' };
    data.categories = (data.categories || []).map(c => typeof c === 'object' ? c.id : c);
    Object.assign(seasonForm, data);
    seasonSectionTab.value = 'form';
};

const fillCategory = (category) => {
    const data = JSON.parse(JSON.stringify(category));
    data.name = data.name || { es: '', en: '' };
    data.description = data.description || { es: '', en: '' };
    Object.assign(categoryForm, data);
    categorySectionTab.value = 'form';
};

const fillCapability = (capability) => {
    const data = JSON.parse(JSON.stringify(capability));
    data.name = data.name || { es: '', en: '' };
    data.description = data.description || { es: '', en: '' };
    Object.assign(capabilityForm, data);
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
        error.value = 'No se pudo iniciar sesión.';
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
    message.value = 'Proyecto guardado.';
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
    message.value = 'Publicación guardada.';
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
    message.value = 'Curso guardado.';
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
    message.value = 'Temporada guardada.';
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
    message.value = 'Categoría guardada.';
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
    message.value = 'Capacidad guardada.';
    capabilitySectionTab.value = 'form';
};

const deleteCapability = async (id) => {
    await api.delete(`/api/admin/capabilities/${id}`);
    await loadAdminData();
};

const saveSocialLinks = async () => {
    await api.put('/api/admin/social-links', { links: socialLinks.value });
    await loadAdminData();
    message.value = 'Redes sociales actualizadas.';
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
        message.value = 'Configuración guardada.';
    } catch {
        error.value = 'El JSON de stack o experiencia no es válido.';
    }
};

const addDetail = () => projectForm.details.push({ label: { es: '', en: '' }, value: { es: '', en: '' } });
const addMedia = () => projectForm.media.push({ kind: 'image', url: '', caption: { es: '', en: '' }, sort_order: projectForm.media.length });
const addSocial = () => socialLinks.value.push({ platform: '', label: { es: '', en: '' }, url: '', icon: '', sort_order: socialLinks.value.length, active: true });

const openPreview = async (type) => {
    const formData = type === 'project' ? projectForm : postForm;
    previewType.value = type;
    previewLocale.value = 'es';
    previewTitle.value = formData.title?.es || formData.title?.en || '';

    try {
        const { data } = await api.post('/api/admin/preview', {
            type,
            locale: 'es',
            data: JSON.parse(JSON.stringify(formData)),
        });
        previewHtml.value = data.html;
        previewTitle.value = data.title;
        showPreviewModal.value = true;
    } catch {
        error.value = 'Error al cargar la vista previa.';
    }
};

const togglePreviewLocale = async () => {
    const newLocale = previewLocale.value === 'es' ? 'en' : 'es';
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
        error.value = 'Error al cargar la vista previa.';
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
                    <button class="ghost-button" @click="logout">Cerrar sesión</button>
                </div>
            </header>

            <div class="admin-tabs">
                <button v-for="item in tabItems" :key="item.key" :class="{ active: tab === item.key }" @click="tab = item.key">
                    {{ item.label }}
                </button>
            </div>

            <p v-if="message" class="success-text">{{ message }}</p>
            <p v-if="error" class="error-text">{{ error }}</p>

            <section v-if="tab === 'projects'">
                <div v-if="projectSectionTab === 'list'" class="panel">
                    <div class="section-heading">
                        <h2>Proyectos</h2>
                        <button class="ghost-button" @click="resetProjectForm">Nuevo</button>
                    </div>
                    <div class="admin-list">
                        <button v-for="project in projects" :key="project.id" class="admin-list-item" @click="fillProject(project)">
                            <span class="star-indicator" :class="{ 'is-featured': project.featured }">★</span>
                            <div class="admin-list-content">
                                <strong>{{ project.title.es || project.title.en }}</strong>
                            </div>
                            <span class="admin-list-status">{{ project.status }}</span>
                        </button>
                    </div>
                </div>
                <form v-if="projectSectionTab === 'form'" class="panel editor-form" @submit.prevent="saveProject">
                    <div class="section-heading">
                        <button class="ghost-button" type="button" @click="projectSectionTab = 'list'">← Volver</button>
                        <h2>{{ projectForm.id ? 'Editar proyecto' : 'Nuevo proyecto' }}</h2>
                    </div>
                    <div class="two-column">
                        <input v-model="projectForm.title.es" placeholder="Título ES">
                        <input v-model="projectForm.title.en" placeholder="Title EN">
                        <input v-model="projectForm.slug" placeholder="Slug">
                        <ImageUploader v-model="projectForm.cover_image_url" label="Imagen de portada" />
                        <input v-model="projectForm.demo_url" placeholder="URL demo">
                        <input v-model="projectForm.repository_url" placeholder="URL repositorio">
                        <input v-model="projectForm.published_at" type="datetime-local">
                        <input v-model="projectForm.stackInput" placeholder="Stack separado por comas">
                    </div>
                    <div class="two-column">
                        <textarea v-model="projectForm.summary.es" rows="3" placeholder="Resumen ES" />
                        <textarea v-model="projectForm.summary.en" rows="3" placeholder="Summary EN" />
                        <textarea v-model="projectForm.description.es" rows="5" placeholder="Descripción ES" />
                        <textarea v-model="projectForm.description.en" rows="5" placeholder="Description EN" />
                    </div>
                    <div class="editor-toggles">
                        <label><input v-model="projectForm.featured" type="checkbox"> Destacado</label>
                        <select v-model="projectForm.status">
                            <option value="draft">Borrador</option>
                            <option value="published">Publicado</option>
                        </select>
                    </div>
                    <div class="sub-editor">
                        <h3>Caso de estudio</h3>
                        <div class="two-column">
                            <textarea v-model="projectForm.problem.es" rows="3" placeholder="Problema ES" />
                            <textarea v-model="projectForm.problem.en" rows="3" placeholder="Problem EN" />
                        </div>
                        <div class="two-column">
                            <textarea v-model="projectForm.approach.es" rows="3" placeholder="Enfoque ES" />
                            <textarea v-model="projectForm.approach.en" rows="3" placeholder="Approach EN" />
                        </div>
                        <div class="two-column">
                            <textarea v-model="projectForm.contribution.es" rows="3" placeholder="Aporte ES" />
                            <textarea v-model="projectForm.contribution.en" rows="3" placeholder="Contribution EN" />
                        </div>
                        <div class="two-column">
                            <textarea v-model="projectForm.what_it_demonstrates.es" rows="3" placeholder="Qué demuestra ES" />
                            <textarea v-model="projectForm.what_it_demonstrates.en" rows="3" placeholder="What it demonstrates EN" />
                        </div>
                    </div>
                    <div class="sub-editor">
                        <div class="section-heading">
                            <h3>Detalles</h3>
                            <button class="ghost-button" type="button" @click="addDetail">Agregar</button>
                        </div>
                        <div v-for="(detail, index) in projectForm.details" :key="index" class="repeat-row">
                            <input v-model="detail.label.es" placeholder="Etiqueta ES">
                            <input v-model="detail.label.en" placeholder="Label EN">
                            <input v-model="detail.value.es" placeholder="Valor ES">
                            <input v-model="detail.value.en" placeholder="Value EN">
                        </div>
                    </div>
                    <div class="sub-editor">
                        <div class="section-heading">
                            <h3>Medios</h3>
                            <button class="ghost-button" type="button" @click="addMedia">Agregar</button>
                        </div>
                        <div v-for="(item, index) in projectForm.media" :key="index" class="repeat-row">
                            <select v-model="item.kind">
                                <option value="image">Imagen</option>
                                <option value="video">Video</option>
                            </select>
                            <ImageUploader
                                v-if="item.kind === 'image'"
                                v-model="item.url"
                                :label="`Imagen ${index + 1}`"
                            />
                            <input v-else v-model="item.url" placeholder="URL del video (YouTube)">
                            <input v-model="item.caption.es" placeholder="Pie ES">
                            <input v-model="item.caption.en" placeholder="Caption EN">
                        </div>
                    </div>
                    <div class="sub-editor">
                        <h3>Categorías</h3>
                        <div class="checkbox-grid">
                            <label v-for="cat in categories" :key="cat.id">
                                <input type="checkbox" :value="cat.id" v-model="projectForm.categories">
                                {{ cat.name?.es || cat.name?.en || cat.slug }}
                            </label>
                        </div>
                    </div>
                    <div class="sub-editor">
                        <h3>Capacidades</h3>
                        <div class="checkbox-grid">
                            <label v-for="cap in capabilities" :key="cap.id">
                                <input type="checkbox" :value="cap.id" v-model="projectForm.capabilities">
                                {{ cap.name?.es || cap.name?.en || cap.slug }}
                            </label>
                        </div>
                    </div>
                    <div class="sub-editor">
                        <h3>Publicaciones relacionadas</h3>
                        <div v-for="group in postsBySeason.groups" :key="group.season.id" class="post-group">
                            <p class="post-group-label">{{ group.season.name?.es || group.season.name?.en || group.season.slug }}</p>
                            <div class="checkbox-grid">
                                <label v-for="post in group.posts" :key="post.id">
                                    <input type="checkbox" :value="post.id" v-model="projectForm.posts">
                                    #{{ post.episode_number }} — {{ post.title?.es || post.title?.en || post.slug }}
                                </label>
                            </div>
                        </div>
                        <div v-if="postsBySeason.ungrouped.length > 0" class="post-group">
                            <p class="post-group-label">Sin temporada</p>
                            <div class="checkbox-grid">
                                <label v-for="post in postsBySeason.ungrouped" :key="post.id">
                                    <input type="checkbox" :value="post.id" v-model="projectForm.posts">
                                    {{ post.title?.es || post.title?.en || post.slug }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="cta-row">
                        <button class="primary-button" type="submit">Guardar proyecto</button>
                        <button class="secondary-button" type="button" @click="openPreview('project')">Vista previa</button>
                        <button v-if="projectForm.id" class="danger-button" type="button" @click="deleteProject(projectForm.id)">Eliminar</button>
                    </div>
                </form>
            </section>

            <section v-if="tab === 'posts'">
                <div v-if="postSectionTab === 'list'" class="panel">
                    <div class="section-heading">
                        <h2>Publicaciones</h2>
                        <button class="ghost-button" @click="resetPostForm">Nueva</button>
                    </div>
                    <div class="admin-list">
                        <button v-for="post in posts" :key="post.id" class="admin-list-item" @click="fillPost(post)">
                            <span class="star-indicator" :class="{ 'is-featured': post.featured }">★</span>
                            <div class="admin-list-content">
                                <strong>{{ post.title.es || post.title.en }}</strong>
                                <span v-if="post.season" class="admin-list-season">{{ post.season.name?.es || post.season.name?.en || post.season.slug }} #{{ post.episode_number }}</span>
                            </div>
                            <div class="admin-list-meta">
                                <span class="admin-list-type">{{ post.type }}</span>
                                <span class="admin-list-status">{{ post.status }}</span>
                            </div>
                        </button>
                    </div>
                </div>
                <form v-if="postSectionTab === 'form'" class="panel editor-form" @submit.prevent="savePost">
                    <div class="section-heading">
                        <button class="ghost-button" type="button" @click="postSectionTab = 'list'">← Volver</button>
                        <h2>{{ postForm.id ? 'Editar publicación' : 'Nueva publicación' }}</h2>
                    </div>
                    <div class="two-column">
                        <input v-model="postForm.title.es" placeholder="Título ES">
                        <input v-model="postForm.title.en" placeholder="Title EN">
                        <input v-model="postForm.slug" placeholder="Slug">
                        <ImageUploader v-model="postForm.cover_image_url" label="Imagen de portada" />
                        <input v-model="postForm.external_url" placeholder="URL externa">
                        <input v-model="postForm.published_at" type="datetime-local">
                    </div>
                    <div class="editor-toggles">
                        <select v-model="postForm.type">
                            <option value="internal">Interna</option>
                            <option value="external">Externa</option>
                        </select>
                        <select v-model="postForm.status">
                            <option value="draft">Borrador</option>
                            <option value="published">Publicado</option>
                        </select>
                        <label><input v-model="postForm.featured" type="checkbox"> Destacada</label>
                        <label><input v-model="postForm.share_enabled" type="checkbox"> Compartir habilitado</label>
                    </div>
                    <div class="two-column">
                        <select v-model="postForm.season_id">
                            <option :value="null">Sin temporada</option>
                            <option v-for="season in seasons" :key="season.id" :value="season.id">{{ season.name?.es || season.name?.en || season.slug }}</option>
                        </select>
                        <input v-model.number="postForm.episode_number" type="number" min="1" placeholder="Número de episodio">
                    </div>
                    <div class="two-column">
                        <textarea v-model="postForm.excerpt.es" rows="3" placeholder="Extracto ES" />
                        <textarea v-model="postForm.excerpt.en" rows="3" placeholder="Excerpt EN" />
                        <textarea v-model="postForm.content.es" rows="8" placeholder="Contenido ES" />
                        <textarea v-model="postForm.content.en" rows="8" placeholder="Content EN" />
                    </div>
                    <div class="cta-row">
                        <button class="primary-button" type="submit">Guardar publicación</button>
                        <button class="secondary-button" type="button" @click="openPreview('post')">Vista previa</button>
                        <button v-if="postForm.id" class="danger-button" type="button" @click="deletePost(postForm.id)">Eliminar</button>
                    </div>
                </form>
            </section>

            <section v-if="tab === 'seasons'">
                <div v-if="seasonSectionTab === 'list'" class="panel">
                    <div class="section-heading">
                        <h2>Temporadas</h2>
                        <button class="ghost-button" @click="resetSeasonForm">Nueva</button>
                    </div>
                    <div class="admin-list">
                        <button v-for="season in seasons" :key="season.id" class="admin-list-item" @click="fillSeason(season)">
                            <div class="admin-list-content">
                                <strong>{{ season.name?.es || season.name?.en || season.slug }}</strong>
                            </div>
                            <span class="admin-list-status">{{ season.status }}</span>
                        </button>
                    </div>
                </div>
                <form v-if="seasonSectionTab === 'form'" class="panel editor-form" @submit.prevent="saveSeason">
                    <div class="section-heading">
                        <button class="ghost-button" type="button" @click="seasonSectionTab = 'list'">← Volver</button>
                        <h2>{{ seasonForm.id ? 'Editar temporada' : 'Nueva temporada' }}</h2>
                    </div>
                    <div class="two-column">
                        <input v-model="seasonForm.name.es" placeholder="Nombre ES">
                        <input v-model="seasonForm.name.en" placeholder="Name EN">
                        <input v-model="seasonForm.slug" placeholder="Slug">
                        <input v-model.number="seasonForm.sort_order" type="number" min="0" placeholder="Orden">
                    </div>
                    <div class="editor-toggles">
                        <select v-model="seasonForm.status">
                            <option value="draft">Borrador</option>
                            <option value="upcoming">Próximamente</option>
                            <option value="active">Activa</option>
                            <option value="completed">Completada</option>
                        </select>
                    </div>
                    <div class="two-column">
                        <textarea v-model="seasonForm.description.es" rows="3" placeholder="Descripción ES" />
                        <textarea v-model="seasonForm.description.en" rows="3" placeholder="Description EN" />
                    </div>
                    <div class="sub-editor">
                        <h3>Categorías</h3>
                        <div v-for="group in categoriesByDimension" :key="group.label" class="post-group">
                            <p class="post-group-label">{{ group.label }}</p>
                            <div class="checkbox-grid">
                                <label v-for="cat in group.items" :key="cat.id">
                                    <input type="checkbox" :value="cat.id" v-model="seasonForm.categories">
                                    {{ cat.name?.es || cat.name?.en || cat.slug }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="cta-row">
                        <button class="primary-button" type="submit">Guardar temporada</button>
                        <button v-if="seasonForm.id" class="danger-button" type="button" @click="deleteSeason(seasonForm.id)">Eliminar</button>
                    </div>
                </form>
            </section>

            <section v-if="tab === 'categories'">
                <div v-if="categorySectionTab === 'list'" class="panel">
                    <div class="section-heading">
                        <h2>Categorías</h2>
                        <button class="ghost-button" @click="resetCategoryForm">Nueva</button>
                    </div>
                    <div class="admin-list">
                        <button v-for="category in categories" :key="category.id" class="admin-list-item" @click="fillCategory(category)">
                            <div class="admin-list-content">
                                <strong>{{ category.name?.es || category.name?.en || category.slug }}</strong>
                            </div>
                            <span class="admin-list-status">{{ category.dimension }}</span>
                        </button>
                    </div>
                </div>
                <form v-if="categorySectionTab === 'form'" class="panel editor-form" @submit.prevent="saveCategory">
                    <div class="section-heading">
                        <button class="ghost-button" type="button" @click="categorySectionTab = 'list'">← Volver</button>
                        <h2>{{ categoryForm.id ? 'Editar categoría' : 'Nueva categoría' }}</h2>
                    </div>
                    <div class="two-column">
                        <input v-model="categoryForm.name.es" placeholder="Nombre ES">
                        <input v-model="categoryForm.name.en" placeholder="Name EN">
                        <input v-model="categoryForm.slug" placeholder="Slug">
                        <select v-model="categoryForm.dimension">
                            <option value="domain">Dominio</option>
                            <option value="capability">Capacidad</option>
                            <option value="technology">Tecnología</option>
                            <option value="methodology">Metodología</option>
                        </select>
                    </div>
                    <div class="two-column">
                        <textarea v-model="categoryForm.description.es" rows="3" placeholder="Descripción ES" />
                        <textarea v-model="categoryForm.description.en" rows="3" placeholder="Description EN" />
                    </div>
                    <div class="cta-row">
                        <button class="primary-button" type="submit">Guardar categoría</button>
                        <button v-if="categoryForm.id" class="danger-button" type="button" @click="deleteCategory(categoryForm.id)">Eliminar</button>
                    </div>
                </form>
            </section>

            <section v-if="tab === 'capabilities'">
                <div v-if="capabilitySectionTab === 'list'" class="panel">
                    <div class="section-heading">
                        <h2>Capacidades</h2>
                        <button class="ghost-button" @click="resetCapabilityForm">Nueva</button>
                    </div>
                    <div class="admin-list">
                        <button v-for="capability in capabilities" :key="capability.id" class="admin-list-item" @click="fillCapability(capability)">
                            <strong>{{ capability.name?.es || capability.name?.en || capability.slug }}</strong>
                        </button>
                    </div>
                </div>
                <form v-if="capabilitySectionTab === 'form'" class="panel editor-form" @submit.prevent="saveCapability">
                    <div class="section-heading">
                        <button class="ghost-button" type="button" @click="capabilitySectionTab = 'list'">← Volver</button>
                        <h2>{{ capabilityForm.id ? 'Editar capacidad' : 'Nueva capacidad' }}</h2>
                    </div>
                    <div class="two-column">
                        <input v-model="capabilityForm.name.es" placeholder="Nombre ES">
                        <input v-model="capabilityForm.name.en" placeholder="Name EN">
                        <input v-model="capabilityForm.slug" placeholder="Slug">
                        <input v-model.number="capabilityForm.sort_order" type="number" min="0" placeholder="Orden">
                    </div>
                    <div class="two-column">
                        <textarea v-model="capabilityForm.description.es" rows="3" placeholder="Descripción ES" />
                        <textarea v-model="capabilityForm.description.en" rows="3" placeholder="Description EN" />
                    </div>
                    <div class="cta-row">
                        <button class="primary-button" type="submit">Guardar capacidad</button>
                        <button v-if="capabilityForm.id" class="danger-button" type="button" @click="deleteCapability(capabilityForm.id)">Eliminar</button>
                    </div>
                </form>
            </section>

            <section v-if="tab === 'courses'" class="admin-grid">
                <div class="panel">
                    <div class="section-heading">
                        <h2>Cursos</h2>
                        <button class="ghost-button" @click="resetCourseForm">Nuevo</button>
                    </div>
                    <div class="admin-list">
                        <button v-for="course in courses" :key="course.id" class="admin-list-item" @click="fillCourse(course)">
                            <div class="admin-list-content">
                                <strong>{{ course.name.es || course.name.en }}</strong>
                            </div>
                            <span class="admin-list-status">{{ course.status }}</span>
                        </button>
                    </div>
                </div>
                <form class="panel editor-form" @submit.prevent="saveCourse">
                    <h2>{{ courseForm.id ? 'Editar curso' : 'Nuevo curso' }}</h2>
                    <div class="two-column">
                        <input v-model="courseForm.name.es" placeholder="Nombre ES">
                        <input v-model="courseForm.name.en" placeholder="Name EN">
                        <input v-model="courseForm.slug" placeholder="Slug">
                        <input v-model="courseForm.issuer" placeholder="Emisor">
                        <input v-model="courseForm.issued_at" type="date">
                        <input v-model="courseForm.credential_id" placeholder="ID credencial (opcional)">
                        <input v-model="courseForm.url" placeholder="URL credencial">
                    </div>
                    <div class="editor-toggles">
                        <label><input v-model="courseForm.featured" type="checkbox"> Destacado</label>
                        <select v-model="courseForm.status">
                            <option value="draft">Borrador</option>
                            <option value="published">Publicado</option>
                        </select>
                    </div>
                    <div class="cta-row">
                        <button class="primary-button" type="submit">Guardar curso</button>
                        <button v-if="courseForm.id" class="danger-button" type="button" @click="deleteCourse(courseForm.id)">Eliminar</button>
                    </div>
                </form>
            </section>

            <section v-if="tab === 'settings'" class="panel editor-form">
                <h2>Configuración del sitio</h2>
                <div class="two-column">
                    <input v-model="settings.home.brand" placeholder="Marca">
                    <input v-model="settings.home.profileImage" placeholder="URL imagen de perfil">
                    <input v-model="settings.home.headline.es" placeholder="Titular ES">
                    <input v-model="settings.home.headline.en" placeholder="Headline EN">
                    <textarea v-model="settings.home.bio.es" rows="4" placeholder="Bio ES" />
                    <textarea v-model="settings.home.bio.en" rows="4" placeholder="Bio EN" />
                    <input v-model="settings.contact.title.es" placeholder="Título contacto ES">
                    <input v-model="settings.contact.title.en" placeholder="Contact title EN">
                    <textarea v-model="settings.contact.subtitle.es" rows="3" placeholder="Subtítulo contacto ES" />
                    <textarea v-model="settings.contact.subtitle.en" rows="3" placeholder="Contact subtitle EN" />
                    <input v-model="settings.contact.email" placeholder="Email de contacto">
                    <input v-model="settings.footer.copyright.es" placeholder="Footer ES">
                    <input v-model="settings.footer.copyright.en" placeholder="Footer EN">
                    <label class="checkbox-row">
                        <input v-model="settings.welcome_modal_enabled" type="checkbox">
                        Modal de bienvenida habilitado
                    </label>
                    <input v-model="settings.welcome_modal_video_url" placeholder="URL video de bienvenida (YouTube embed)">
                </div>
                <label>Stack JSON</label>
                <textarea v-model="stackJson" rows="12" />
                <label>Experiencia JSON</label>
                <textarea v-model="experienceJson" rows="12" />
                <button class="primary-button" type="button" @click="saveSettings">Guardar configuración</button>
            </section>

            <section v-if="tab === 'social'" class="panel editor-form">
                <div class="section-heading">
                    <h2>Redes sociales</h2>
                    <button class="ghost-button" type="button" @click="addSocial">Agregar</button>
                </div>
                <div v-for="(item, index) in socialLinks" :key="index" class="repeat-row">
                    <input v-model="item.platform" placeholder="Plataforma">
                    <input v-model="item.icon" placeholder="Icono">
                    <input v-model="item.label.es" placeholder="Etiqueta ES">
                    <input v-model="item.label.en" placeholder="Label EN">
                    <input v-model="item.url" placeholder="URL">
                    <label><input v-model="item.active" type="checkbox"> Activo</label>
                </div>
                <button class="primary-button" type="button" @click="saveSocialLinks">Guardar redes sociales</button>
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
