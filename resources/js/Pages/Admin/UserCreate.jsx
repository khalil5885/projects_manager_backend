import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm, Link } from '@inertiajs/react';

export default function UserCreate() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        global_role: '',
    });

    function handleSubmit(e) {
        e.preventDefault();
        post('/admin/users', {
            onSuccess: () => reset(),
        });
    }

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Create New User
                    </h2>
                    <Link
                        href="/admin/users"
                        className="text-sm text-gray-500 hover:text-gray-700 transition"
                    >
                        ← Back to Users
                    </Link>
                </div>
            }
        >
            <Head title="Create User" />

            <div className="py-12">
                <div className="mx-auto max-w-2xl sm:px-6 lg:px-8">

                    {/* Card */}
                    <div className="bg-white shadow-sm rounded-2xl overflow-hidden">

                        {/* Top accent bar */}
                        <div className="h-1.5 w-full bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500" />

                        <div className="p-8">
                            <div className="mb-8">
                                <h3 className="text-2xl font-bold text-gray-900 tracking-tight">
                                    New Account
                                </h3>
                                <p className="mt-1 text-sm text-gray-500">
                                    A welcome email with login credentials will be sent automatically.
                                </p>
                            </div>

                            <form onSubmit={handleSubmit} className="space-y-6">

                                {/* Name */}
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                        Full Name
                                    </label>
                                    <input
                                        type="text"
                                        value={data.name}
                                        onChange={e => setData('name', e.target.value)}
                                        placeholder="John Doe"
                                        className={`w-full px-4 py-2.5 rounded-lg border text-gray-900 text-sm
                                            placeholder-gray-400 bg-gray-50 focus:bg-white
                                            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                            transition
                                            ${errors.name ? 'border-red-400 bg-red-50' : 'border-gray-200'}`}
                                    />
                                    {errors.name && (
                                        <p className="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                            <span>⚠</span> {errors.name}
                                        </p>
                                    )}
                                </div>

                                {/* Email */}
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                        Email Address
                                    </label>
                                    <input
                                        type="email"
                                        value={data.email}
                                        onChange={e => setData('email', e.target.value)}
                                        placeholder="john@example.com"
                                        className={`w-full px-4 py-2.5 rounded-lg border text-gray-900 text-sm
                                            placeholder-gray-400 bg-gray-50 focus:bg-white
                                            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                            transition
                                            ${errors.email ? 'border-red-400 bg-red-50' : 'border-gray-200'}`}
                                    />
                                    {errors.email && (
                                        <p className="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                            <span>⚠</span> {errors.email}
                                        </p>
                                    )}
                                </div>

                                {/* Role */}
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                        Role
                                    </label>
                                    <div className="grid grid-cols-2 gap-3">
                                        {['employee', 'client'].map((global_role) => (
                                            <button
                                                key={global_role}
                                                type="button"
                                                onClick={() => setData('global_role', global_role)}
                                                className={`relative flex items-center gap-3 px-4 py-3 rounded-lg border-2 text-sm font-medium transition cursor-pointer
                                                    ${data.global_role === global_role
                                                        ? 'border-blue-500 bg-blue-50 text-blue-700'
                                                        : 'border-gray-200 bg-gray-50 text-gray-600 hover:border-gray-300 hover:bg-gray-100'
                                                    }`}
                                            >
                                                <span className="text-lg">
                                                    {global_role === 'employee' ? '👤' : '🏢'}
                                                </span>
                                                <span className="capitalize">{global_role}</span>
                                                {data.global_role === global_role && (
                                                    <span className="ml-auto text-blue-500">✓</span>
                                                )}
                                            </button>
                                        ))}
                                    </div>
                                    {errors.global_role && (
                                        <p className="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                            <span>⚠</span> {errors.role}
                                        </p>
                                    )}
                                </div>

                                {/* Info box */}
                                <div className="flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-lg px-4 py-3">
                                    <span className="text-blue-400 mt-0.5">ℹ</span>
                                    <p className="text-xs text-blue-600 leading-relaxed">
                                        A secure password will be auto-generated and sent to the user's email along with their login instructions.
                                    </p>
                                </div>

                                {/* Actions */}
                                <div className="flex items-center justify-end gap-3 pt-2">
                                    <Link
                                        href="/admin/users"
                                        className="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition"
                                    >
                                        Cancel
                                    </Link>
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-60 disabled:cursor-not-allowed rounded-lg transition flex items-center gap-2"
                                    >
                                        {processing ? (
                                            <>
                                                <svg className="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                                    
                                                </svg>
                                                Creating...
                                            </>
                                        ) : (
                                            'Create User'
                                        )}
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}