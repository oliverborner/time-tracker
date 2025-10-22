import { ref } from 'vue'

export function useProjects() {
  const projects = ref<any[]>([])
  const loading = ref(false)

  const fetchAll = async () => {
    loading.value = true
    const res = await fetch('http://127.0.0.1:8000/api/projects', {
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
    })
    if (res.ok) projects.value = await res.json()
    loading.value = false
  }

  const create = async (project: { name: string; description: string }) => {
    const res = await fetch('http://127.0.0.1:8000/api/projects', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
      body: JSON.stringify(project), 
    });
  
    if (!res.ok) throw new Error('Failed to create project');
    return await res.json();
  };

  const remove = async (id: number) => {
    await fetch(`http://127.0.0.1:8000/api/projects/${id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
    })
    projects.value = projects.value.filter(p => p.id !== id)
  }

  return { projects, fetchAll, create, remove, loading }
}



/* 
  const projects = ref<Project[]>([
    { id: 1, name: 'Website Redesign 🔥', description:'A more modern look and feel.' },
    { id: 2, name: 'Mobile App [Flutter]', description:'Cordova a no go? Lets flutter now' },
    { id: 3, name: 'Marketing Campaign', description:'Socialize ;-)' },
    { id: 4, name: 'Implementing a reusable tokenized Design System', description:'That would be awesome!' },
  ]);

*/