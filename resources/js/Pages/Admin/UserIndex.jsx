import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, usePage } from '@inertiajs/react';

export default function UserIndex({ users = [] }) {
    const { flash } = usePage().props;

    const roleBadge = (role) => {
        const styles = {
            admin: 'bg-purple-100 text-purple-700',
            employee: 'bg-blue-100 text-blue-700',
            customer: 'bg-green-100 text-green-700',
        };
        return (
            <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize ${styles[role] ?? 'bg-gray-100 text-gray-600'}`}>
                {role}
            </span>
        );
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Users
                    </h2>
                    <Link
                        href={route('admin.users.create')}
                        className="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition"
                    >
                        + New User
                    </Link>
                </div>
            }
        >
            <Head title="Users" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-4">

                    {/* Flash success */}
                    {flash?.success && (
                        <div className="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                            <span>✓</span>
                            <span>{flash.success}</span>
                        </div>
                    )}

                    {/* Table card */}
                    <div className="bg-white shadow-sm rounded-xl overflow-hidden">

                        {users.length === 0 ? (
                            <div className="p-12 text-center text-gray-400 text-sm">
                                No users found. <Link href={route('admin.users.create')} className="text-indigo-600 hover:underline">Create one.</Link>
                            </div>
                        ) : (
                            <table className="min-w-full divide-y divide-gray-100">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                                        <th className="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                                        <th className="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                                        <th className="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-100">
                                    {users.map((user) => (
                                        <tr key={user.id} className="hover:bg-gray-50 transition">
                                            <td className="px-6 py-4">
                                                <div className="flex items-center gap-3">
                                                    <div className="h-8 w-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-semibold">
                                                        {user.name.charAt(0).toUpperCase()}
                                                    </div>
                                                    <span className="text-sm font-medium text-gray-900">{user.name}</span>
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 text-sm text-gray-500">{user.email}</td>
                                            <td className="px-6 py-4">{roleBadge(user.role)}</td>
                                            <td className="px-6 py-4 text-sm text-gray-400">
                                                {new Date(user.created_at).toLocaleDateString('en-GB', {
                                                    day: '2-digit', month: 'short', year: 'numeric'
                                                })}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        )}
                    </div>

                    {/* Count */}
                    <p className="text-xs text-gray-400 text-right">
                        {users.length} user{users.length !== 1 ? 's' : ''} total
                    </p>

                </div>
            </div>
        </AuthenticatedLayout>
    );
}